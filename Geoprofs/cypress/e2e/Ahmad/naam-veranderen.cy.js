describe('Login, Navigate to Profile, and Update Phone Number', () => {
    it('should log in, navigate to profile, and update the user phone number', () => {

      cy.visit('http://localhost:8000/login');

      cy.get('input[name="email"]').type('User02@gmail.com');
      cy.get('input[name="password"]').type('User02');
      cy.get('button[type="submit"]').click();

      cy.url().should('include', '/2fa');
      cy.visit('http://localhost:8000/dashboard');

      cy.url().should('include', '/dashboard');
      cy.contains('Dashboard').should('be.visible');

      cy.visit('http://localhost:8000/profiel');

      cy.get('#voornaam').clear();
      cy.get('#voornaam').type('Khaled');
      cy.get('button[type="submit"]').contains('Opslaan').click();


      cy.get('#voornaam').should('have.value', 'Khaled');

    });
});
