describe('Login with Two-Factor Authentication', () => {
    it('should log in and display the 2FA screen', () => {
        // Visit the login page
        cy.visit('http://localhost:8000/login');
    
        // Input email
        cy.get('input[name="email"]').type('wessam@gmail.com');
        cy.get('input[name="password"]').type('Wess');
        cy.get('button[type="submit"]').click();
        cy.url().should('include', '/2fa');
        cy.contains('Two-Factor Authentication').should('be.visible');
        cy.get('input[name="code"]').type('123456'); // Wrong code
        cy.get('button[type="button"]').click();
        cy.contains('An error occurred. Please try again later.').should('be.visible');
    });
});

