describe('Create Account', () => {
	it('Navigate to Create Account page', () => {
    	cy.visit('http://10.147.20.66/frontend/createAccount.html')
    	cy.contains('Sign Up')
  	})

  	it('Trying to submit w/o info', () => {
      cy.get('.signupbtn').click()
      cy.url().should('not.equal','http://10.147.20.66/frontend/login.html')
    })

    it('Pressing cancel button', () => {
      cy.get('.cancelbtn').click()
      cy.url().should('equal','http://10.147.20.66/frontend/login.html')
    })

    it('Renavigating back to Create Account page', () => {
  		cy.get('.medium-editor-element').click()
  		cy.url().should('eq', 'http://10.147.20.66/frontend/createAccount.html')
  	})

	it('Submitting username already in system', () => {
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

      cy.get('.repeat')
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

      cy.get('.signupbtn').click()
      cy.url().should('equal','http://10.147.20.66/frontend/createAccount.html')
  	})

	it('Submitting two different passwords', () => {
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
      .type('random0@gmail.com')

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

      cy.get('.repeat')
  		// .type() with special character sequences
      .type('{leftarrow}{rightarrow}{uparrow}{downarrow}')
      .type('{del}{selectall}{backspace}')

      // .type() with key modifiers
      .type('{alt}{option}') //these are equivalent
      .type('{ctrl}{control}') //these are equivalent
      .type('{meta}{command}{cmd}') //these are equivalent
      .type('{shift}')
      //typing in password
      .type('asd')

      cy.get('.signupbtn').click()
      cy.url().should('equal','http://10.147.20.66/frontend/createAccount.html')
  	})

  	it('Submitting properly', () => {
      cy.get('.repeat').type('f')

      cy.get('.signupbtn').click()
      cy.url().should('equal','http://10.147.20.66/frontend/homepage.html')
  	})
})