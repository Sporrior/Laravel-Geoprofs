describe('Login, Navigate to Settings, and Change password', () => {
    it('should log in, navigate to settings, and change the user password', () => {
      cy.visit('http://localhost:8000/login');

      cy.get('input[name="email"]').type('User01@gmail.com');
      cy.get('input[name="password"]').type('User01');
      cy.get('button[type="submit"]').click();

      cy.url().should('include', '/2fa');
      cy.visit('http://localhost:8000/dashboard');

      cy.url().should('include', '/dashboard');
      cy.contains('Dashboard').should('be.visible');

      cy.visit('http://localhost:8000/profiel');


      cy.get('#huidigWachtwoord').type('User01', { log: false });
      cy.get('#nieuwWachtwoord').type('User010', { log: false });
      cy.get('#nieuwWachtwoord_confirmation').type('User010', { log: false });
      cy.get('.wachtwoord-wijzigen .btn-primary').click();

      // Verify password change success
      cy.get('.alert-success').should('be.visible').and('contain', 'Wachtwoord succesvol gewijzigd');

      // Log out
      cy.get('button[aria-label="Log out"]').click();

      // Log in with new password
      cy.visit('http://localhost:8000/login');
      cy.get('input[name="email"]').type('User01@gmail.com');
      cy.get('input[name="password"]').type('User010', { log: false });
      cy.get('button[type="submit"]').click();

      // Verify successful login
      cy.url().should('include', '/dashboard');
      cy.contains('Dashboard').should('be.visible');

    });
});
