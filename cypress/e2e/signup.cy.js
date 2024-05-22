describe('Formulaire de Connexion', () => {
    it('test 1 - signup OK', () => {
        cy.visit('http://localhost:8313/signup');

        cy.get('#registration_firstname').type('John');
        cy.get('#registration_lastname').type('Doe');
        cy.get('#registration_email').type('johndoe@test.com');
        cy.get('#registration_plainPassword_first').type('password');
        cy.get('#registration_plainPassword_second').type('password');

        cy.get('button[type="submit"]').click();

        cy.contains('Votre compte a été créé avec succès. Vous pouvez maintenant vous connecter.').should('exist');
    });

    it('test 2 - signup KO', () => {
        cy.visit('http://localhost:8313/signup');

        cy.get('#registration_firstname').type('Jane');
        cy.get('#registration_lastname').type('Doe');
        cy.get('#registration_email').type('janedoe@test.com');
        cy.get('#registration_plainPassword_first').type('password');
        cy.get('#registration_plainPassword_second').type('mtodepasse');

        cy.get('button[type="submit"]').click();

        cy.contains('Créer mon compte').should('exist');
    });
});