describe('Tijdelijk blokkeren', function () {
    it('Tijdelijk blokkeren', function () {
        cy.visit('http://localhost:8000/login');

        for (let i = 0; i < 3; i++) {
            cy.get('input[name="email"]').clear().type('wessam@gmail.com');
            cy.get('input[name="password"]').clear().type('VERKEERDE_Wachtwoord');
            cy.get('button[type="submit"]').click();

            // Controleer of de foutmelding zichtbaar is
            cy.get('div.alert-danger').should('be.visible');
            if (i >= 2) {
                cy.get('div.alert-danger').should('contain', 'Too many failed login attempts. Try again in 5 minutes.');
            } else {
                cy.get('div.alert-danger').should('contain', 'The provided credentials do not match our records.');
            }

        }

        // Na de 3e poging kun je hier logica toevoegen om te controleren of het account geblokkeerd is
        cy.get('div.alert-danger').should('contain', 'Too many failed login attempts. Try again in 5 minutes.');
    });
});