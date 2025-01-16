describe('Login with Two-Factor Authentication That is below 6 Numbers', () => {
    it('should log in and display the 2FA screen', () => {
        // Visit the login page
        cy.visit('http://localhost:8000/login');
    
        // Input email
        cy.get('input[name="email"]').type('wessam@gmail.com');
        cy.get('input[name="password"]').type('Wess');
        cy.get('button[type="submit"]').click();
        cy.url().should('include', '/2fa');
        cy.contains('Two-Factor Authentication').should('be.visible');
        cy.get('input[name="code"]').type('1234'); // Wrong code
        cy.get('button[type="button"]').click();
        cy.contains('Please enter a valid 6-digit code.').should('be.visible');
    });
});