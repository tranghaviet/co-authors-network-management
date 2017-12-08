<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Co-author network</title>

  <!-- CSS  -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  {!! Html::style('css/materialize.css') !!}
  {!! Html::style('css/style.css') !!}
</head>

<body>
    <div class="navbar-fixed">
        <nav>
          <div class="nav-wrapper" style="background: white !important;">
            <a href="#" class="brand-logo">Logo</a>
            <ul class="right hide-on-med-and-down">
              
              <li><a href="">Home</a></li>
              <li><a href="#">About</a></li>
              <li><a href="#">Contact</a></li>
            </ul>
          </div>
        </nav>
      </div>

  <div id="index-banner" class="parallax-container">
    <div class="section no-pad-bot">
      <div class="container">
        <br>
        <br>
        <h1 class="header center teal-text text-lighten-2">Xay dung mang dong tac gia</h1>
        <!-- <div class="row center">
          <div class="col m2"></div>
         <div class="col m7">
            <input type="text">
         </div>
         <div class="col m3">
            <a class="waves-effect waves-light btn "><i class="material-icons left">search</i>Search</a>
            
         </div>
       
        </div> -->
        <div class="row center">
            <a class="waves-effect waves-light btn" href="{{ route('user.author') }}">Author</a>

            <a class="waves-effect waves-light btn" href="{{ route('user.paper') }}">Paper</a>

            <a class="waves-effect waves-light btn" href="{{ route('user.authorPaper') }}">Author Paper</a>

            <a class="waves-effect waves-light btn" href="{{ route('user.co-authors') }}">Co-authors</a>

            <a class="waves-effect waves-light btn" href="{{ route('user.candidate') }}">Candidate</a>

            
            
          <!-- <a href="#" id="download-button" class="btn-large waves-effect waves-light teal lighten-1">Author</a>
          <a href="#" id="download-button" class="btn-large waves-effect waves-light teal lighten-1">Papers</a>
          <a href="#" id="download-button" class="btn-large waves-effect waves-light teal lighten-1"> Co-authors</a>
          <a href="#" id="download-button" class="btn-large waves-effect waves-light teal lighten-1">Candidate</a>
          <a href="#" id="download-button" class="btn-large waves-effect waves-light teal lighten-1">Universities</a> -->
        </div>
        <br>
        <br>

      </div>
    </div>
    <div class="parallax">
      {!! Html::image('img/background1.jpg') !!}
    </div>
  </div>

  <div class="about">
    <div class="row">
      <div class="col s6 m2">
        <h1>ABOUT</h1>
      </div>

      <div class="col s6 m8">
        <div class="row">
          <h1 style="text-align:center">SCI-HUB</h1>
          <p>the first pirate website in the world to provide mass and public access to tens of millions of research papers</p>
        </div>
        <div class="row">
          A research paper is a special publication written by scientists to be read by other researchers. Papers are primary sources
          neccessary for research – for example, they contain detailed description of new results and experiments.
        </div>
        <div class="row">
          papers we have in our library: more than 64,500,000 and growing
        </div>
        <div>
          At this time the widest possible distribution of research papers, as well as of other scientific or educational sources,
          is artificially restricted by copyright laws. Such laws effectively slow down the development of science in human
          society. The Sci-Hub project, running from 5th September 2011, is challenging the status quo. At the moment, Sci-Hub
          provides access to hundreds of thousands research papers every day, effectively bypassing any paywalls and restrictions.
        </div>

      </div>
    </div>
  </div>



  <div class="container">
    <div class="row">
      <h1 style="text-align:center">Sci-Hub</h1>
    </div>
    <div class="section">
      <!--   Icon Section   -->
      <div class="row">
        <div class="col s12 m4">
          <div class="icon-block">
            <!-- <h2 class="center brown-text">
              <i class="material-icons">flash_on</i>
            </h2> -->
            <h5 class="center">knowledge to all</h5>

            <p class="light">We did most of the heavy lifting for you to provide a default stylings that incorporate our custom components.
              Additionally, we refined animations and transitions to provide a smoother experience for developers.</p>
          </div>
        </div>

        <div class="col s12 m4">
          <div class="icon-block">
            <!-- <h2 class="center brown-text">
              <i class="material-icons">group</i>
            </h2> -->
            <h5 class="center">no copyright </h5>

            <p class="light">By utilizing elements and principles of Material Design, we were able to create a framework that incorporates
              components and animations that provide more feedback to users. Additionally, a single underlying responsive
              system across all platforms allow for a more unified user experience.</p>
          </div>
        </div>

        <div class="col s12 m4">
          <div class="icon-block">
            <!-- <h2 class="center brown-text">
              <i class="material-icons">settings</i>
            </h2> -->
            <h5 class="center">open access
            </h5>

            <p class="light">We have provided detailed documentation as well as specific code examples to help new users get started. We are
              also always open to feedback and can answer any questions a user may have about Materialize.</p>
          </div>
        </div>
      </div>

    </div>
  </div>


  <div class="parallax-container valign-wrapper">
    <div class="section no-pad-bot">
      <div class="container">
        <div class="row center">
          <h5 class="header col s12 light">A modern responsive front-end framework based on Material Design</h5>
        </div>
      </div>
    </div>
    <div class="parallax">
      {!! Html::image('img/background2.jpg') !!}
    </div>
  </div>

  <div class="container">
    <div class="section">

      <div class="row">
        <div class="col s12 center">
          <h3>
            <i class="mdi-content-send brown-text"></i>
          </h3>
          <h4>Contact Us</h4>
          <p class="left-align light">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam scelerisque id nunc nec volutpat. Etiam pellentesque
            tristique arcu, non consequat magna fermentum ac. Cras ut ultricies eros. Maecenas eros justo, ullamcorper a
            sapien id, viverra ultrices eros. Morbi sem neque, posuere et pretium eget, bibendum sollicitudin lacus. Aliquam
            eleifend sollicitudin diam, eu mattis nisl maximus sed. Nulla imperdiet semper molestie. Morbi massa odio, condimentum
            sed ipsum ac, gravida ultrices erat. Nullam eget dignissim mauris, non tristique erat. Vestibulum ante ipsum
            primis in faucibus orci luctus et ultrices posuere cubilia Curae;</p>
        </div>
      </div>

    </div>
  </div>


  <div class="parallax-container valign-wrapper">
    <div class="section no-pad-bot">
      <div class="container">
        <div class="row center">
          <h5 class="header col s12 light">A modern responsive front-end framework based on Material Design</h5>
        </div>
      </div>
    </div>
    <div class="parallax">
      {!! Html::image('img/background3.jpg') !!}
    </div>
  </div>

  <footer class="page-footer teal">
    <div class="container">
      <div class="row">
        <div class="col l6 s12">
          <h5 class="white-text">Company Bio</h5>
          <p class="grey-text text-lighten-4">We are a team of college students working on this project like it's our full time job. Any amount would help support
            and continue development on this project and is greatly appreciated.</p>

        </div>
        <div class="col l3 s12">
          <h5 class="white-text">Settings</h5>
          <ul>
            <li>
              <a class="white-text" href="#">Link 1</a>
            </li>
            <li>
              <a class="white-text" href="#">Link 2</a>
            </li>
            <li>
              <a class="white-text" href="#">Link 3</a>
            </li>
            <li>
              <a class="white-text" href="#">Link 4</a>
            </li>
          </ul>
        </div>
        <div class="col l3 s12">
          <h5 class="white-text">Connect</h5>
          <ul>
            <li>
              <a class="white-text" href="#">Link 1</a>
            </li>
            <li>
              <a class="white-text" href="#">Link 2</a>
            </li>
            <li>
              <a class="white-text" href="#">Link 3</a>
            </li>
            <li>
              <a class="white-text" href="#">Link 4</a>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="footer-copyright">
      <div class="container">
        Made by
        <a class="brown-text text-lighten-3" href="http://materializecss.com">Materialize</a>
      </div>
    </div>
  </footer>


  <!--  Scripts-->
    <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
  {!! Html::script('js/materialize.js') !!}
  {!! Html::script('js/init.js') !!}
</body>

</html>
