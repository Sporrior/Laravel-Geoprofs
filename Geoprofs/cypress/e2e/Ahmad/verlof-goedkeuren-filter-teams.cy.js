describe('Keuring Page - Team Filter Functionality', () => {
    it('should navigate to the Keuring page and verify team filter functionality', () => {
      // Visit the login page
      cy.visit('http://localhost:8000/login');

      // Log in with valid credentials
      cy.get('input[name="email"]').type('ahmad@gmail.com');
      cy.get('input[name="password"]').type('Ahmad', );
      cy.get('button[type="submit"]').click();

      cy.url().should('include', '/2fa');
      cy.visit('http://localhost:8000/dashboard');

      cy.url().should('include', '/dashboard');
      cy.contains('Dashboard').should('be.visible');

      // Navigate to the Keuring page
      cy.visit('http://localhost:8000/keuring');
      cy.url().should('include', '/keuring');

      // Open the Team filter dropdown
      cy.get('[data-control="checkbox-dropdown"] .dropdown-label').contains('Team').click();

      // Select a specific team by its name
      const teamName = 'HRM';
      cy.get('.dropdown-list .dropdown-option').contains(teamName).find('input[type="checkbox"]').check();

      // Apply the filter
      cy.get('button[type="submit"]').contains('Filter').click();

    });
  });
