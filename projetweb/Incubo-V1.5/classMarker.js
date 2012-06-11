var CMarker = new function() {
    this.id;
    this.auteurFiche;
    this.numerosFiche;
    this.adresse;
    this.commune;
    this.zipCode;
    this.natureOpération;

    this.getadressecomplete = function () {
        return this.adresse + ' ' + this.commune + this.zipCode;
    };
    
    
}
var Animal = Class.create({ // On creer notre classe nommé Animal  
    // Constructeur qui prend en paramètre le nom et le bruit de l'animal  
    initialize: function(nom, bruit) {  
        this.nom  = nom; // On assigne chaque paramètre à une variable  
        this.bruit = bruit;  
    },  
  
    crier: function() { // On creer une méthode de la classe Animal nommer crier  
        // Cette méthode affiche une alerte avec le nom de l'animal et son cri  
        alert(this.nom + " fait: " + this.bruit + "!");  
    }  
});  
/* 
On va tester notre classe 
On creer une variable qui va contenir l'instance d'un Animal 
qui va prendre en paramèter le nom et le cri de l'animal 
*/  
var chat = new Animal("Chat","miaou");  
chat.crier(); // on appelle la méthode crier de notre instance  
//-> alert "Chat fait: miaou!"  