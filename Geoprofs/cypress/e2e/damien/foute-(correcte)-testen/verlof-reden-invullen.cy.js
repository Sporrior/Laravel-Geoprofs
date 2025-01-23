describe('Verlofaanvraag Form Submission', () => {
    it('should show an error when the reason field is left blank', () => {
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
  
      cy.get('#startDatum').then(($input) => {
        const nativeInput = $input[0];
        nativeInput._flatpickr.setDate('2025-01-30'); 
      });
  
      cy.get('#eindDatum').then(($input) => {
        const nativeInput = $input[0];
        nativeInput._flatpickr.setDate('2025-02-05');
      });
  
      cy.get('#verlof_reden').clear();
      cy.get('#verlof_soort').select('Vakantie');
  
      cy.get('.submit-button').click();
  
      cy.get('textarea[required]').then(($textarea) => {
        expect($textarea[0].validationMessage).to.eq('Vul dit veld in');
      });
    });
  });
  