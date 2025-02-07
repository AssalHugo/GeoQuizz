-- Adminer 4.8.1 PostgreSQL 17.2 (Debian 17.2-1.pgdg120+1) dump

DROP TABLE IF EXISTS "users";

CREATE TABLE "public"."users" (
    "id" uuid NOT NULL,
    "password" character varying NOT NULL
) WITH (oids = false);

INSERT INTO "users" ("id", "password") VALUES
  ('d9247dde-aab2-4790-9ddf-53c3107c7f62','$2y$10$6Kk1H1IRa1agqVtjZF2oqOeKo6Idg9KDJ.mA8jjZz.8TXnjq6lbF2'), -- willy72
  ('e1d95011-32b0-469a-b0ff-09ec21e9f598','$2y$10$r1u8LYFb.8OPfihpnvxQIutiUgUkZrCSdPYcOgp7flbh6QcswLA12'), -- corona493
  ('c0ab4fc4-7829-4e96-8515-9f35a7b1c704','$2y$10$VHIq1hw9DLWOzsU4hbLIUui6CcQhro6EF8YCiZVu.FWRle07z/6KG'); -- password1999

-- 2025-02-07 10:37:03.386541+00