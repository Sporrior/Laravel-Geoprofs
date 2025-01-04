describe('Login, Navigate to Account Toevoegen, and Add Account', () => {
    it('should log in, navigate to account toevoegen, and add a new account', () => {
      cy.visit('http://localhost:8000/login');
  
      cy.get('input[name="email"]').type('email');
      cy.get('input[name="password"]').type('password');
      cy.get('button[type="submit"]').click();
  
      cy.url().should('include', '/2fa');
      cy.visit('http://localhost:8000/dashboard');
  
      cy.url().should('include', '/dashboard');
      cy.contains('Dashboard').should('be.visible');
    });
});