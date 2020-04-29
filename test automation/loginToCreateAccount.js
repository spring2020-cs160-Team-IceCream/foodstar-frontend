describe('Login to Create Account', () => {
	it('Navigate to login page', () => {
    	cy.visit('http://10.147.20.66/frontend/login.html#')
    	cy.contains('FoodStar')
  		cy.contains('Login')
  	})

  	it('Submitting', () => {
  		cy.get('.medium-editor-element').click()
  		cy.url().should('eq', 'http://10.147.20.66/frontend/createAccount.html')
  	})
})