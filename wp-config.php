<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier contient les réglages de configuration suivants : réglages MySQL,
 * préfixe de table, clés secrètes, langue utilisée, et ABSPATH.
 * Vous pouvez en savoir plus à leur sujet en allant sur
 * {@link http://codex.wordpress.org/fr:Modifier_wp-config.php Modifier
 * wp-config.php}. C’est votre hébergeur qui doit vous donner vos
 * codes MySQL.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d’installation. Vous n’avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en "wp-config.php" et remplir les
 * valeurs.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define('DB_NAME', 'w4');

/** Utilisateur de la base de données MySQL. */
define('DB_USER', 'root');

/** Mot de passe de la base de données MySQL. */
define('DB_PASSWORD', '');

/** Adresse de l’hébergement MySQL. */
define('DB_HOST', 'localhost');

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define('DB_CHARSET', 'utf8mb4');

/** Type de collation de la base de données.
  * N’y touchez que si vous savez ce que vous faites.
  */
define('DB_COLLATE', '');

/**#@+
 * Clés uniques d’authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clefs secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n’importe quel moment, afin d’invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'cG*OM])%*=IZt$9`78iZE#-A`D UC5&`Z} $g/2!4I[[~!=S9X#dUDEV<3:$ OVC');
define('SECURE_AUTH_KEY',  'Jt2veF/hjNCtwv1[Hau(}5u+zDNWKE4i^NUDa6K+>3p]aVH?g=e |Ou-/{euP7?=');
define('LOGGED_IN_KEY',    ';zi,gLf &nDA@F ps 3YW<[c_uL3/oLJOu7KdG_.{OV#u(gG~?1B v+|@ BpE:(=');
define('NONCE_KEY',        'hxV1vVT/9VoQ#_A&il/xRg%G7q|/:-<_m bH*h31{tQbq(shv9)FfXq=8IPgylmN');
define('AUTH_SALT',        'll4!^-9F,BPrkbqx$?-!IF0>_ZXk{CLv]x{[*VsBk:9?:BnkZLyG@ffB~qlsd2W?');
define('SECURE_AUTH_SALT', '5Zuk:Z1je(kK;TsOH~dUY8l=_}[1GNGKhh*Pl0}79|svggm.lWyK)^*Alq|>Z4s=');
define('LOGGED_IN_SALT',   '5:#NNtTrpB#lOp-*v_{PXlQ(,~KjO #%oVS8x+r?NTh70EQ%BS11M(lk>7J|?O? ');
define('NONCE_SALT',       '9~Dh)t#twUha<yk}&z;]/E68 /Lpa{JM}pY&r15T|t~r)J&~L;[aS[$bUOyH(:6V');
/**#@-*/

define( 'WP_AUTO_UPDATE_CORE', false );

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique.
 * N’utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés !
 */
$table_prefix  = 'wp_';

/**
 * Pour les développeurs : le mode déboguage de WordPress.
 *
 * En passant la valeur suivante à "true", vous activez l’affichage des
 * notifications d’erreurs pendant vos essais.
 * Il est fortemment recommandé que les développeurs d’extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de
 * développement.
 *
 * Pour plus d’information sur les autres constantes qui peuvent être utilisées
 * pour le déboguage, rendez-vous sur le Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* C’est tout, ne touchez pas à ce qui suit ! */

/** Chemin absolu vers le dossier de WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');