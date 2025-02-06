CREATE TABLE "series" (
  "id" INT GENERATED BY DEFAULT AS IDENTITY PRIMARY KEY,
  "titre" varchar,
  "latitude" decimal,
  "longitude" decimal,
  "largeur" int
);

CREATE TABLE "photos" (
  "id" INT GENERATED BY DEFAULT AS IDENTITY PRIMARY KEY,
  "photo" varchar,
  "latitude" decimal,
  "longitude" decimal,
  "adresse" varchar,
  "serie_id" int
);

-- ALTER TABLE "photos" ADD FOREIGN KEY ("serie_id") REFERENCES "series" ("id");
