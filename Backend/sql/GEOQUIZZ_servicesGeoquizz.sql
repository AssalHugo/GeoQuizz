CREATE TABLE "Game" (
  "id" UUID PRIMARY KEY,
  "serie_id" int,
  "score" int,
  "state" int,
  "user_id" UUID
);

CREATE TABLE "user" (
  "id" UUID PRIMARY KEY,
  "nickname" varchar,
  "auth_id" UUID
);

ALTER TABLE "Game" ADD FOREIGN KEY ("user_id") REFERENCES "user" ("id");
