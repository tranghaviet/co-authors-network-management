<?php

namespace App\Helpers\Envato;

use App\Models\Author;
use Excel;
use App\Models\Country;
use App\Models\Subject;
use App\Models\City;
use App\Models\University;
use App\Models\AuthorSubject;

class ImportAuthorData
{
    public static function import_authors()
    {
        $UNKNOWN = 'UNKNOWN';
        if (Input::hasFile('file')) {
            $path = Input::file('file')->getRealPath();
            $data = Excel::load($path, function ($reader) {
            })->get();
            if (! empty($data) && $data->count()) {
                foreach ($data as $key => $value) {
                    if (! empty($value->id)) {
                        $affiliation = preg_split('/,\s*/', $value->affiliation);
                        $n = count($affiliation);
                        $university = array_key_exists(0, $affiliation) ? $affiliation[0] : $UNKNOWN;
                        if (array_key_exists($n - 1, $affiliation) && $n - 1 > 0) {
                            $country = $affiliation[$n - 1];
                        } else {
                            $country = $UNKNOWN;
                        }
                        if (array_key_exists($n - 2, $affiliation) && $n - 2 > 0) {
                            $city = $affiliation[$n - 2];
                        } else {
                            $city = $UNKNOWN;
                        }

                        $country_id = handle_country($country, $UNKNOWN);
                        if (! $country_id) {
                            continue;
                        }
                        // City
                        $city_id = handle_city($city, $country_id, $UNKNOWN);
                        if (! $city_id) {
                            continue;
                        }
                        // University
                        $university_id = handle_university($university, $city_id, $UNKNOWN);
                        if (! $university_id) {
                            continue;
                        }

                        $id = $value->id;
                        $surname = $value->surname;
                        $given_name = $value->givenname;
                        $email = $value->email;
                        $url = $value->url;
                        insert_authors($id, $surname, $given_name, $email, $url, $university_id);
                        handle_subjects($id, $subjects);
                    }
                }
            }
        }
    }

    // vuducdung
    public static function insert_authors($id, $surname, $given_name, $email, $url, $university_id)
    {
        if (! $university_id) {
            if (! Author::where(['id' => $id])->exists()) {
                $author = new Author();
                $author->id = $id;
                $author->given_name = $given_name;
                $author->surname = $surname;
                $author->email = $email;
                $author->url = $url;
                $author->university_id = null;
                $author->save();
            }
        } else {
            if (! Author::where(['id' => $id])->exists()) {
                $author = new Author();
                $author->id = $id;
                $author->given_name = $given_name;
                $author->surname = $surname;
                $author->email = $email;
                $author->url = $url;
                $author->university_id = $university_id;
                $author->save();
            }
        }
    }

    public static function handle_country($country, $UNKNOWN)
    {
        preg_match('/^\s*$/', $country, $matches);

        if (count($matches) > 0) {
            $country = $UNKNOWN;
        }
        if (! Country::where(['name' => $country])->exists()) {
            // If not exist, create one
            $new_country = new Country;
            $new_country->name = $country;
            $new_country->save();
        } else {
            $new_country = Country::where('name', '=', $country)->first();
        }

        return $new_country->id;
    }

    public static function handle_city($city, $country_id, $UNKNOWN)
    {
        preg_match('/^\s*$/', $city, $matches);
        if (count($matches) > 0) {
            $city = $UNKNOWN;
        }

        if (! City::where([['name', '=', $city], ['country_id', '=', $country_id]])->exists()) {
            $new_city = new City;
            $new_city->name = $city;
            $new_city->country_id = $country_id;
            $new_city->save();
        } else {
            $new_city = City::where([['name', '=', $city], ['country_id', '=', $country_id]])->first();
        }

        return $new_city->id;
    }

    public static function handle_university($university, $city_id, $UNKNOWN)
    {
        preg_match('/^\s*$/', $university, $matches);

        if (count($matches) > 0) {
            $university = $UNKNOWN;
        }

        if (! University::where([['name', '=', $university], ['city_id', '=', $city_id]])->exists()) {
            $new_university = new University;
            $new_university->name = $university;
            $new_university->city_id = $city_id;
            $new_university->save();
        } else {
            $new_university = University::where([['name', '=', $university], ['city_id', '=', $city_id]])->first();
        }

        return $new_university->id;
    }

    public static function handle_subjects($author_id, $subjects)
    {
        // $array_subject = preg_split('/,\s*/', trim($subjects), -1, PREG_SPLIT_NO_EMPTY);
        $array_subject = preg_split('/,\s*/', strtolower($subjects), -1, PREG_SPLIT_NO_EMPTY);

        foreach ($array_subject as $subject) {
            preg_match('/^\s*$/', $subject, $matches);
            if (count($matches) > 0) {
                continue;
            }

            if (! Subject::where(['name' => $subject])->exists()) {
                $new_subject = new Subject;
                $new_subject->name = $subject;
                $new_subject->save();
            } else {
                $new_subject = Subject::where('name', '=', $subject)->first();
            }
            if (! AuthorSubject::where([['author_id', '=', $author_id], ['subject_id', '=', $new_subject->id]])->exists()) {
                $new_AuthorSubject = new AuthorSubject;
                $new_AuthorSubject->author_id = $author_id;
                $new_AuthorSubject->subject_id = $new_subject->id;
                $new_AuthorSubject->save();
            }
            // $author_subject=Subject::find($new_subject->id);
                        // $author_subject->authors()->attach($author_id);
        }
    }
}
