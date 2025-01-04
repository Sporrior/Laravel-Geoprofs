describe('Login, Navigate to Verlofaanvraag, and Submit Leave Request', () => {
  it('should log in, navigate to Verlofaanvraag, and submit a leave request', () => {
    cy.visit('http://localhost:8000/login');

    cy.get('input[name="email"]').type('damien@gmail.com');
    cy.get('input[name="password"]').type('Damien12345');
    cy.get('button[type="submit"]').click();

    cy.url().should('include', '/2fa');
    cy.visit('http://localhost:8000/dashboard');

    cy.url().should('include', '/dashboard');
    cy.contains('Dashboard').should('be.visible');

    cy.contains('Verlof').click();
    cy.url().should('include', '/verlofaanvragen');
    cy.contains('Verlofaanvraag').should('be.visible');

    cy.get('input[placeholder="dd-mm-jjjj"]').eq(0).type('18-12-2024');
    cy.get('input[placeholder="dd-mm-jjjj"]').eq(1).type('20-12-2024');
    cy.get('textarea').type('Vakantie met familie');

    cy.get('select').select('Vakantie');

    cy.contains('Verlofaanvraag Versturen').click();

    cy.contains('Verlofaanvraag successfully submitted').should('be.visible');
  });
});