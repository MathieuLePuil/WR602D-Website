describe('Formulaire de Connexion', () => {
    it('test 1 - connexion OK', () => {
        cy.visit('http://localhost:8313/login');

        cy.get('#email').type('lepuilmathieu@gmail.com');
        cy.get('#password').type('password');

        cy.get('button[type="submit"]').click();

        cy.contains('Mettre à niveau').should('exist');
    });

    it('test 2 - connexion KO', () => {
        cy.visit('http://localhost:8313/login');

        cy.get('#email').type('test@test.fr');
        cy.get('#password').type('test');

        cy.get('button[type="submit"]').click();

        cy.contains('Invalid credentials.').should('exist');
    });
});