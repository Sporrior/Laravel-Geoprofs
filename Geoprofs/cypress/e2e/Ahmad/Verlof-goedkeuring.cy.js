describe('Submit and Approve Leave Request', () => {
    it('should log in, submit a leave request, and approve it', () => {
      // Log in
      cy.visit('http://localhost:8000/login');
      cy.get('input[name="email"]').type('ahmad@gmail.com');
      cy.get('input[name="password"]').type('Ahmad', );
      cy.get('button[type="submit"]').click();

      // Handle 2FA if necessary
      cy.url().should('include', '/2fa');
      // Add steps to handle 2FA if applicable

      // Navigate to dashboard
      cy.visit('http://localhost:8000/dashboard');
      cy.url().should('include', '/dashboard');
      cy.contains('Dashboard').should('be.visible');

      // Navigate to Verlofaanvraag
      cy.contains('Verlof').click();
      cy.url().should('include', '/verlofaanvragen');
      cy.contains('Verlofaanvraag').should('be.visible');

      // Fill out and submit the leave request form
      cy.get('input[placeholder="dd-mm-jjjj"]').eq(0)
      .invoke('val', '2024-12-18')
      .trigger('change');

    // Set the end date
    cy.get('input[placeholder="dd-mm-jjjj"]').eq(1)
      .invoke('val', '2024-12-20')
      .trigger('change');

      cy.get('textarea').type('vakantie');
      cy.get('select#verlof_soort').select('Vakantie');
      cy.contains('Verlofaanvraag Versturen').click();

      // Navigate to Verlof Goedkeuring
      cy.contains('Verlof Goedkeuring').click();
      cy.url().should('include', '/verlof-goedkeuring');
      cy.contains('Verlof Goedkeuring').should('be.visible');

      // Locate and approve the submitted leave request
      cy.contains('Ahmad')
        .parent()
        .within(() => {
          cy.contains('Pending').should('be.visible');
          cy.contains('Approve').click();
        });

      // Verify the request status is updated to Approved
      cy.contains('Ahmad')
        .parent()
        .within(() => {
          cy.contains('Approved').should('be.visible');
        });
    });
  });
