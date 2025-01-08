describe('Login, Navigate to Account Toevoegen, and Add Account', () => {
  it('should log in, navigate to account toevoegen, and add a new account', () => {
    cy.visit('http://localhost:8000/login');
  
    cy.get('input[name="email"]').type('damien@gmail.com');
    cy.get('input[name="password"]').type('Damien12345');
    cy.get('button[type="submit"]').click();
  
    cy.url().should('include', '/2fa');
    cy.visit('http://localhost:8000/dashboard');
  
    cy.url().should('include', '/dashboard');
    cy.contains('Dashboard').should('be.visible');
  
    cy.contains('Account Toevoegen').click();
  
    cy.url().should('include', '/account-toevoegen');
    cy.contains('Account Toevoegen').should('be.visible');
  
    cy.get('input[placeholder="Bijv. John"]').type('John');
    cy.get('input[placeholder="Bijv. van der"]').type('van der');
    cy.get('input[placeholder="Bijv. Doe"]').type('Doe');
    cy.get('input[placeholder="+31 6 12345678"]').type('+31 6 12345678');
    cy.get('input[placeholder="Bijv. john.doe@example.com"]').type('john.doe@example.com');
    cy.get('input[placeholder="Minimaal 8 karakters"]').type('Password123');
    cy.get('input[placeholder="Bevestig het wachtwoord"]').type('Password123');
  
    cy.get('select').select('werknemer');
  
    cy.get('button').contains('Account Aanmaken').click();
  
    cy.contains('Gebruiker succesvol aangemaakt.').should('be.visible');
  });
});