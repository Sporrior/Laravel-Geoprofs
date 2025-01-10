describe('Login with wrong password', () => {
    it('should display an error massage', () => {
        // Visit the login page
        cy.visit('http://localhost:8000/login');
    
        cy.get('div.alert-danger').should('not.exist');
        cy.get('input[name="email"]').type('wessam@gmail.com');
        cy.get('input[name="password"]').type('VERKEERD_Wachtwoord11');
        cy.get('button[type="submit"]').click();
        cy.get('div.alert-danger').should('be.visible');
        cy.get('div.alert-danger').should('contain', 'The provided credentials do not match our records.');

    });
});

