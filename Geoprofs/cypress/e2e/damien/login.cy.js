describe('Login with Two-Factor Authentication', () => {
    it('should log in and display the 2FA screen', () => {
      // Visit the login page
      cy.visit('http://localhost:8000/login');
  
      // Input email
      cy.get('input[name="email"]').type('damien@gmail.com');
  
      // Input password
      cy.get('input[name="password"]').type('Damien12345');
  
      // Click the login button
      cy.get('button[type="submit"]').click();
  
      // Verify that the 2FA screen appears
      cy.url().should('include', '/fa'); // URL check for 2FA page
      cy.contains('Two-Factor Authentication').should('be.visible'); // Check for the 2FA header
      cy.get('input[placeholder="Enter the 6-digit code"]').should('be.visible'); // Verify the input field
      cy.get('button').contains('Verify').should('be.visible'); // Verify the Verify button
    });
  });