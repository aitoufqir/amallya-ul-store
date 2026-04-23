USE ecommerce_db;

DELETE FROM produits;

INSERT INTO produits (nom, description, prix, categorie, image) VALUES
('Robe Lina Rose', 'Robe legere et elegante pour un look feminin au quotidien.', 50, 'robes', 'images/pic1.png'),
('Robe Sofia Chic', 'Modele habille avec coupe simple et finition soignee.', 50, 'robes', 'images/pic2.png'),
('Robe Yasmina Fleurie', 'Robe fraiche a porter pour les sorties et les occasions.', 140, 'robes', 'images/pic3.png'),
('Robe Salma Beige', 'Piece douce et moderne facile a assortir.', 160, 'robes', 'images/pic4.png'),
('Veste Nora Classique', 'Veste pratique avec style epure pour tenues de ville.', 110, 'vestes', 'images/pic5.png'),
('Blazer Amal Noir', 'Blazer elegant parfait pour bureau et sorties.', 150, 'vestes', 'images/pic6.png'),
('Veste Imane Denim', 'Veste casual tendance et confortable.', 175, 'vestes', 'images/pic7.png'),
('Veste Sara Camel', 'Coupe chic avec couleur chaude et moderne.', 190, 'vestes', 'images/pic8.png'),
('Jean Aya Bleu', 'Jean simple et moderne pour tous les jours.', 70, 'jeans', 'images/pic9.png'),
('Jean Ines Noir', 'Jean noir sobre avec belle coupe ajustee.', 85, 'jeans', 'images/pic10.png'),
('Jean Kenza Large', 'Jean tendance avec coupe ample et style actuel.', 105, 'jeans', 'images/pic11.png'),
('Jean Maha Taille Haute', 'Modele flatteur qui allonge la silhouette.', 125, 'jeans', 'images/pic12.png'),
('Robe Dounia Ivoire', 'Robe claire avec lignes propres et allure delicate.', 135, 'robes', 'images/pic13.png'),
('Robe Nada Satin', 'Robe elegante pour look doux et raffine.', 155, 'robes', 'images/pic14.png'),
('Veste Hiba Creme', 'Veste lumineuse parfaite pour style chic.', 180, 'vestes', 'images/pic15.png'),
('Veste Ghita Soft', 'Veste legere avec finition moderne.', 200, 'vestes', 'images/pic16.png'),
('Escarpins Leila Nude', 'Chaussures elegantes et faciles a porter.', 130, 'chaussures', 'images/pic17.png'),
('Bottines Maryam Noires', 'Bottines stylees avec presence forte.', 170, 'chaussures', 'images/pic18.png'),
('Sandales Hana Dorees', 'Sandales fines pour looks habilles.', 90, 'chaussures', 'images/pic19.png'),
('Mocassins Rita Urbains', 'Chaussures confortables pour usage quotidien.', 115, 'chaussures', 'images/pic20.png'),
('Doudoune Ikram Noire', 'Veste chaude et moderne pour un look hiver simple et chic.', 145, 'vestes', 'images/pic21.png'),
('Pull Wiam Raye', 'Pull doux a rayures avec style casual et confortable.', 165, 'vestes', 'images/pic22.png'),
('Manteau Manel Beige', 'Manteau long elegant avec ceinture et finition propre.', 210, 'vestes', 'images/pic23.png'),
('Jean Asma Coupe Large', 'Jean large moderne avec taille haute et tombe net.', 230, 'jeans', 'images/pic24.png'),
('Bomber Farah Bordeaux', 'Bomber tendance avec coupe courte et esprit urbain.', 250, 'vestes', 'images/pic25.png'),
('T-shirt Zineb Noir', 'T-shirt noir casual facile a porter chaque jour.', 295, 'vestes', 'images/pic26.png');
