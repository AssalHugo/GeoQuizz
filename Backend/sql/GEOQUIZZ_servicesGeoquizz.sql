CREATE TABLE "game" (
    "id" VARCHAR(48) PRIMARY KEY,
    "userId" UUID NOT NULL,
    "photoIds" JSONB NOT NULL,
    "serieId" VARCHAR(48) NOT NULL,
    "score" INT DEFAULT 0 NOT NULL,
    "state" VARCHAR(20) NOT NULL,
    "currentPhotoIndex" INT DEFAULT 0 NOT NULL,
    "startTime" TIMESTAMP DEFAULT NULL
);

CREATE TABLE "user" (
  "id" UUID PRIMARY KEY,
  "nickname" varchar,
  "email" varchar
);

ALTER TABLE "game" ADD FOREIGN KEY ("userId") REFERENCES "user" ("id");
