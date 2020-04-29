describe('Homepage to Create Post', () => {
	it('Navigate to home page', () => {
    	cy.visit('http://10.147.20.66/frontend/homepage.html')
    	cy.contains('Food Star')
  	})
	it('Click on create post', () => {
		cy.get('.create-post').click()
	})
})