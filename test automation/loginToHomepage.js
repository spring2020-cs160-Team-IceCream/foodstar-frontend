describe('Login to Homepage', () => {
	it('Navigate to login page', () => {
    	cy.visit('http://10.147.20.66/frontend/login.html#')
    	cy.contains('FoodStar')
  		cy.contains('Login')
  	})
/*
  it('Trying to submit w/o info', () => {
      cy.get('.login').submit()
      cy.url().should('not.equal','http://10.147.20.66/frontend/homepage.html')
    })*/

	it('Typing in info', () => {
  		cy.get('.username')
  		// .type() with special character sequences
      .type('{leftarrow}{rightarrow}{uparrow}{downarrow}')
      .type('{del}{selectall}{backspace}')

      // .type() with key modifiers
      .type('{alt}{option}') //these are equivalent
      .type('{ctrl}{control}') //these are equivalent
      .type('{meta}{command}{cmd}') //these are equivalent
      .type('{shift}')
      //typing in username
      .type('random@gmail.com')

	  	cy.get('.password')
  		// .type() with special character sequences
      .type('{leftarrow}{rightarrow}{uparrow}{downarrow}')
      .type('{del}{selectall}{backspace}')

      // .type() with key modifiers
      .type('{alt}{option}') //these are equivalent
      .type('{ctrl}{control}') //these are equivalent
      .type('{meta}{command}{cmd}') //these are equivalent
      .type('{shift}')
      //typing in password
      .type('asdf')
  	})

  	it('Submitting', () => {
  		cy.get('.login').submit()
  		cy.url().should('eq', 'http://10.147.20.66/frontend/homepage.html')
  	})
})