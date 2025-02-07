-- Adminer 4.8.1 PostgreSQL 17.2 (Debian 17.2-1.pgdg120+1) dump

DROP TABLE IF EXISTS "game";

CREATE TABLE "public"."game" (
    "id" character varying(48) NOT NULL,
    "userId" uuid NOT NULL,
    "photoIds" jsonb NOT NULL,
    "serieId" character varying(48) NOT NULL,
    "score" integer DEFAULT '0' NOT NULL,
    "state" character varying(20) NOT NULL,
    "currentPhotoIndex" integer DEFAULT '0' NOT NULL,
    "startTime" timestamp,
    CONSTRAINT "game_pkey" PRIMARY KEY ("id")
) WITH (oids = false);

DROP TABLE IF EXISTS "user";

CREATE TABLE "public"."user" (
    "id" uuid NOT NULL,
    "nickname" character varying,
    "email" character varying NOT NULL,
    CONSTRAINT "user_email" UNIQUE ("email"),
    CONSTRAINT "user_pkey" PRIMARY KEY ("id")
) WITH (oids = false);

INSERT INTO "user" ("id", "nickname", "email") VALUES 
  ('d9247dde-aab2-4790-9ddf-53c3107c7f62','Anne','anne.willow@outlook.com'),
  ('e1d95011-32b0-469a-b0ff-09ec21e9f598','Mariko','mariko@gmail.com'),
  ('c0ab4fc4-7829-4e96-8515-9f35a7b1c704','Tate','tate@gmail.com');

ALTER TABLE ONLY "public"."game" ADD CONSTRAINT "game_userId_fkey" FOREIGN KEY ("userId") REFERENCES "user"(id) ON DELETE CASCADE NOT DEFERRABLE;

-- 2025-02-07 10:07:03.324417+00