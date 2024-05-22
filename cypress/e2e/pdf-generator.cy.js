describe('Formulaire de Connexion', () => {
    it('test 1 - generation OK', () => {
        cy.visit('http://localhost:8313/login');

        cy.get('#email').type('lepuilmathieu@gmail.com');
        cy.get('#password').type('password');

        cy.get('button[type="submit"]').click();

        cy.visit('http://localhost:8313/generate-pdf');

        cy.get('#form_url').type('https://www.google.fr/');

        cy.get('button[type="submit"]').click();

        cy.contains('Le PDF a été généré.').should('exist');
    });

    it('test 2 - generation KO', () => {
        cy.visit('http://localhost:8313/login');

        cy.get('#email').type('lepuilmathieu@gmail.com');
        cy.get('#password').type('password');

        cy.get('button[type="submit"]').click();

        cy.visit('http://localhost:8313/generate-pdf');

        cy.get('#form_url').type('test');
        cy.get('button[type="submit"]').click();

        cy.contains('Veuillez entrer une URL valide.').should('exist');
    });
});