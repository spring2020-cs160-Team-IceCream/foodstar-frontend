import React from "react";
import { render } from "react-dom";
import "./index.css";

const App = () => (
  <section class="main food_background">
    <div class="layer">
      <div class="bottom-grid">
        <div class="logo">
					<h1> </h1>
				</div>
      </div>
      <div class="content-w3ls">
        <div class="title">
					<h1>FoodStar</h1>
				</div>
        <div class="content-bottom">
					<form action="#" method="post">
            <div class="field-group editContent">
							<div class="icon-field">
								<span class="fa fa-envelope" aria-hidden="true" id="ui-id-2"></span>
							</div>
							<div class="wthree-field">
								<input name="email" id="email" type="text" placeholder="Email" required=""/>
							</div>
						</div>
            <div class="field-group editContent">
							<div class="icon-field">
								<span class="fa fa-lock" aria-hidden="true"></span>
							</div>
							<div class="wthree-field">
								<input name="password" id="myInput" type="Password" placeholder="Password"/>
							</div>
						</div>
            <ul class="list-login">
							<li>
							</li>
							<li>
								<a href="#" class="text-right editContent">Forgot Password?</a>
							</li>
							<li class="clearfix"></li>
						</ul>
            <div class="wthree-field">
							<button type="submit" class="btn">Login</button>
						</div>
            
						<ul class="list-login-bottom">
							<li class="">
								<a href="#" class="editContent medium-editor-element" contenteditable="true" spellcheck="true" data-medium-editor-element="true" role="textbox" aria-multiline="true" data-medium-editor-editor-index="13" medium-editor-index="97f948df-618b-1849-eff2-a7c015fad069" data-placeholder="Type your text">Create Account</a>
							</li>
						</ul>
          </form>
        </div>
			</div>
    </div>
	</section>
);
render(
  <App />,
  document.getElementById("root")
);
