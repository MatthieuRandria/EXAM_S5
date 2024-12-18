CREATE VIEW v_info_user AS
SELECT
FROM Users U JOIN Genre G ON U.idGenre=G.idGenre
JOIN Role R ON U.idRole=R.idRole;