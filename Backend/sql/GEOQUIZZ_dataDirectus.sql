-- Insérer une seule série pour Nancy, France
INSERT INTO "series" ("titre", "latitude", "longitude", "largeur") VALUES
('Carte de Nancy, France', 48.6884047, 6.1820169, 14201);

-- Insérer 50 photos avec des coordonnées différentes autour de Nancy
INSERT INTO "photos" ("photo", "latitude", "longitude", "adresse", "serie_id") VALUES
('photo1.png', 48.6921, 6.1844, '2 Rue Claude Charles, 54000 Nancy, France', 1),
('photo2.png', 48.6935, 6.1761, '15 Place Carnot, 54000 Nancy, France', 1),
('photo3.png', 48.6948, 6.1703, '57 Rue Isabey, 54000 Nancy, France', 1),
('photo4.png', 48.6959, 6.1628, '151bis Avenue de la Libération, 54100 Nancy, France', 1),
('photo5.png', 48.6973, 6.1572, '168 Avenue de la Libération, 54100 Nancy, France', 1),
('photo6.png', 48.6992, 6.1506, '7 Rue de la Bergamote, 54100 Nancy, France', 1),
('photo7.png', 48.7015, 6.1451, '131 Rue Nicolas Appert, 54100 Nancy, France', 1),
('photo8.png', 48.7037, 6.1387, '15 Rue Blaise Pascal, 54320 Maxéville, France', 1),
('photo9.png', 48.7056, 6.1324, 'SharePrint, Avenue du Général de Gaulle, 54320 Maxéville, France', 1),
('photo10.png', 48.7079, 6.1268, '6 Rue Hubert Curien, 54320 Maxéville, France', 1),
('photo11.png', 48.7101, 6.1213, 'Route du Souvenir Français, 54250 Champigneulles, France', 1),
('photo12.png', 48.7123, 6.1159, 'Route du Souvenir Français, 54250 Champigneulles, France', 1),
('photo13.png', 48.693722, 6.183409, '14 Place Stanislas, 54000 Nancy, France', 1),
('photo14.png', 48.697780, 6.174986, '54 Cours Léopold, 54000 Nancy, France', 1),
('photo15.png', 48.682315, 6.159736, '42 Allée Neuve, 54520 Laxou, France', 1),
('photo16.png', 48.685974, 6.161094, '2 Rue Sergent Bobillot, 54000 Nancy, France', 1),
('photo17.png', 48.700070, 6.172750, '34 Rue de Metz, 54100 Nancy, France', 1),
('photo18.png', 48.688838, 6.167150, '82 Rue Raymond Poincaré, 54100 Nancy, France', 1),
('photo19.png', 48.677077, 6.153317, '11 Rue du Général de Castelnau, 54600 Villers-lès-Nancy, France', 1),
('photo20.png', 48.668904, 6.146242, '11 Rue Saint Fiacre, 54600 Villers-lès-Nancy, France', 1),
('photo21.png', 48.666630, 6.142820, '19 Rue du Chanoine Piéron, 54600 Villers-lès-Nancy, France', 1),
('photo22.png', 48.669380, 6.161622, '4d Allée du Muguet, 54000 Nancy, France', 1),
('photo23.png', 48.694484, 6.184164, '3 Place Nelson Mandela, 54000 Nancy, France', 1),
('photo24.png', 48.690693, 6.180051, '8 Rue de la Visitation, 54100 Nancy, France', 1),
('photo25.png', 48.679028, 6.169645, '39 Avenue du Maréchal Juin, 54000 Nancy, France', 1),
('photo26.png', 48.688049, 6.172113, '27 Avenue Foch, 54100 Nancy, France', 1),
('photo27.png', 48.684174, 6.175911, '18 bis Rue Thierry Alix, 54100 Nancy, France', 1),
('photo28.png', 48.702488, 6.194481, '9 Rue Jacques Cartier, 54130 Saint-Max, France', 1),
('photo29.png', 48.709083, 6.207978, '48 Rue de la Haie le Comte, 54130 Saint-Max, France', 1),
('photo30.png', 48.693056, 6.178611, '11 Rue des Michottes, 54100 Nancy, France', 1),
('photo31.png', 48.674870, 6.143640, 'Théâtre La Cachette, Rue de l''Asnée, 54520 Laxou, France', 1),
('photo32.png', 48.681280, 6.162370, '30 Boulevard Charlemagne, 54100 Nancy, France', 1),
('photo33.png', 48.693870, 6.185160, '3 Rue Lyautey, 54100 Nancy, France', 1),
('photo34.png', 48.688590, 6.174760, '18 Avenue Foch, 54000 Nancy, France', 1),
('photo35.png', 48.670708, 6.159404, '33 Avenue de Brabois, 54600 Villers-lès-Nancy, France', 1),
('photo36.png', 48.689678, 6.177970, '9 Rue Chanzy, 54000 Nancy, France', 1),
('photo37.png', 48.701236, 6.190580, '30 Rue du Docteur Grandjean, 54100 Nancy, France', 1),
('photo38.png', 48.686150, 6.172090, '63bis Rue Jeanne d''Arc, 54100 Nancy, France', 1),
('photo39.png', 48.683111, 6.171274, '104 Rue de Mon Désert, 54100 Nancy, France', 1),
('photo40.png', 48.694167, 6.182222, '23 Rue Héré, 54000 Nancy, France', 1),
('photo41.png', 48.679660, 6.162860, '47 Rue Emile Bertin, 54000 Nancy, France', 1),
('photo42.png', 48.690230, 6.174410, '3 Place Simone Veil, 54100 Nancy, France', 1),
('photo43.png', 48.698497, 6.176510, '2ter Rue de la Craffe, 54000 Nancy, France', 1),
('photo44.png', 48.676920, 6.168310, '78 Rue du Sergent Blandan, 54100 Nancy, France', 1),
('photo45.png', 48.689770, 6.181020, '13 Rue des Ponts, 54100 Nancy, France', 1),
('photo46.png', 48.704667, 6.198222, '10 Rue Hector Berlioz, 54130 Saint-Max, France', 1),
('photo47.png', 48.711250, 6.201780, '8 Rue des Bégonias, 54130 Saint-Max, France', 1),
('photo48.png', 48.690590, 6.180470, '33 Rue de la Visitation, 54100 Nancy, France', 1),
('photo49.png', 48.688120, 6.177860, '13 Boulevard Joffre, 54000 Nancy, France', 1),
('photo50.png', 48.685722, 6.168556, '2 Rue de Belfort, 54100 Nancy, France', 1);
