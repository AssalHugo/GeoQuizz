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

ALTER TABLE ONLY "public"."game" ADD CONSTRAINT "game_userId_fkey" FOREIGN KEY ("userId") REFERENCES "user"(id) ON DELETE CASCADE NOT DEFERRABLE;

-- 2025-02-07 10:07:03.324417+00