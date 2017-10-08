<!-- Co Author Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('co_author_id', 'Co Author Id:') !!}
    {!! Form::number('co_author_id', null, ['class' => 'form-control']) !!}
</div>

<!-- No Of Mutual Authors Field -->
<div class="form-group col-sm-6">
    {!! Form::label('no_of_mutual_authors', 'No Of Mutual Authors:') !!}
    {!! Form::number('no_of_mutual_authors', null, ['class' => 'form-control']) !!}
</div>

<!-- No Of Joint Papers Field -->
<div class="form-group col-sm-6">
    {!! Form::label('no_of_joint_papers', 'No Of Joint Papers:') !!}
    {!! Form::number('no_of_joint_papers', null, ['class' => 'form-control']) !!}
</div>

<!-- No Of Joint Subjects Field -->
<div class="form-group col-sm-6">
    {!! Form::label('no_of_joint_subjects', 'No Of Joint Subjects:') !!}
    {!! Form::number('no_of_joint_subjects', null, ['class' => 'form-control']) !!}
</div>

<!-- No Of Joint Keywords Field -->
<div class="form-group col-sm-6">
    {!! Form::label('no_of_joint_keywords', 'No Of Joint Keywords:') !!}
    {!! Form::number('no_of_joint_keywords', null, ['class' => 'form-control']) !!}
</div>

<!-- Score 1 Field -->
<div class="form-group col-sm-6">
    {!! Form::label('score_1', 'Score 1:') !!}
    {!! Form::number('score_1', null, ['class' => 'form-control', 'step'=>'any']) !!}
</div>

<!-- Score 2 Field -->
<div class="form-group col-sm-6">
    {!! Form::label('score_2', 'Score 2:') !!}
    {!! Form::number('score_2', null, ['class' => 'form-control', 'step'=>'any']) !!}
</div>

<!-- Score 3 Field -->
<div class="form-group col-sm-6">
    {!! Form::label('score_3', 'Score 3:') !!}
    {!! Form::number('score_3', null, ['class' => 'form-control', 'step'=>'any']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('candidates.index') !!}" class="btn btn-default">Cancel</a>
</div>
