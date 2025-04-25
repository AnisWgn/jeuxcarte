-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : sam. 05 avr. 2025 à 19:00
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `trading_card_game`
--

-- --------------------------------------------------------

--
-- Structure de la table `cards`
--

CREATE TABLE `cards` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `available_quantity` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `cards`
--

INSERT INTO `cards` (`id`, `name`, `description`, `image_url`, `available_quantity`, `created_at`) VALUES
(2, 'France', 'La France, ou la République française, est un pays souverain situé principalement en Europe de l\'Ouest, mais aussi dans diverses régions ultramarines. Ces territoires ultramarins lui confèrent une position transcontinentale, avec des présences dans les océans Atlantique, Pacifique, Indien, et même en Antarctique et en Amérique du Sud.\r\n\r\nEn Europe, la France partage ses frontières terrestres avec plusieurs pays : la Belgique, le Luxembourg, l\'Allemagne, la Suisse, l\'Italie, l\'Espagne, Monaco et l\'Andorre. En outre, elle a également des frontières terrestres avec le Brésil, le Suriname et les Pays-Bas via ses territoires d\'outre-mer en Amérique du Sud.\r\n\r\nLa France possède des façades maritimes importantes sur les mers et océans qui bordent ses territoires : l\'Atlantique, la Méditerranée, le Pacifique et l\'océan Indien. Ces étendues maritimes permettent au pays de disposer de la deuxième zone économique exclusive (ZEE) la plus vaste au monde.', 'https://upload.wikimedia.org/wikipedia/commons/c/c3/Flag_of_France.svg', 98, '2025-03-24 22:04:27'),
(3, 'Allemagne', 'L\'Allemagne, ou la République fédérale d\'Allemagne (Bundesrepublik Deutschland en allemand), est un État situé en Europe centrale, bien que certaines définitions incluent également l\'Europe de l\'Ouest. Le pays est bordé par plusieurs mers et pays : au nord, la mer du Nord, le Danemark et la mer Baltique ; à l\'est, la Pologne et la Tchéquie ; au sud, l\'Autriche et la Suisse ; à l\'ouest, la France, la Belgique, le Luxembourg, et les Pays-Bas.\r\n\r\nL\'Allemagne est un État décentralisé et fédéral, composé de 16 États fédéraux. Elle abrite quatre grandes métropoles de plus d\'un million d\'habitants : la capitale Berlin, ainsi que Hambourg, Munich et Cologne. Le siège du gouvernement est à Berlin, mais la ville de Bonn, ancienne capitale de l\'Allemagne de l\'Ouest, reste un centre administratif important.\r\n\r\nFrancfort-sur-le-Main est souvent considérée comme la capitale financière de l\'Allemagne, puisqu\'elle abrite le siège de la Banque centrale européenne. La langue officielle du pays est principalement l\'allemand, bien que d\'autres langues régionales ou minoritaires soient également parlées.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/ba/Flag_of_Germany.svg/1920px-Flag_of_Germany.svg.png', 97, '2025-03-24 22:05:21'),
(4, 'Belgique', 'La Belgique, officiellement le Royaume de Belgique, est un pays situé en Europe de l\'Ouest, bordé par la France, les Pays-Bas, l\'Allemagne, le Luxembourg et la mer du Nord. Le pays couvre une superficie de 30 688 km² et comptait une population de 11 748 716 habitants au 1er janvier 2024, avec une densité de 383 habitants par km².\r\n\r\nLa Belgique est une monarchie constitutionnelle fédérale avec un régime parlementaire. Sa capitale est Bruxelles, qui est également le siège de nombreuses institutions internationales, dont l\'Union européenne et l\'OTAN. Parmi les autres grandes villes du pays figurent Anvers, Gand, Charleroi, Liège, Bruges, Namur et Louvain.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/65/Flag_of_Belgium.svg/1024px-Flag_of_Belgium.svg.png', 98, '2025-03-24 22:06:03'),
(5, 'Autriche', 'L\'Autriche (en allemand : Österreich), en forme longue la République d\'Autriche (en allemand : Republik Österreich), est un État fédéral d\'Europe centrale, sans accès à la mer. Pays montagneux, il est entouré, dans le sens des aiguilles d\'une montre, par l\'Allemagne et la Tchéquie au nord, la Slovaquie et la Hongrie à l\'est, la Slovénie et l\'Italie au sud, ainsi que par la Suisse et le Liechtenstein à l\'ouest. Sa capitale est Vienne, la plus grande ville du pays.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/41/Flag_of_Austria.svg/1280px-Flag_of_Austria.svg.png', 100, '2025-03-25 08:34:01'),
(6, 'Bulgarie', 'La Bulgarie, en forme longue la République de Bulgarie (en bulgare : България et Република България, translittération : Bălgarija et Republika Bălgarija), est un pays d\'Europe du Sud-Est situé dans les Balkans. Elle est bordée par la mer Noire à l\'est, par la Grèce et la Turquie au sud, par le Danube et la Roumanie au nord, et par la Serbie et la Macédoine du Nord à l\'ouest. Sa capitale est Sofia.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/9/9a/Flag_of_Bulgaria.svg/1920px-Flag_of_Bulgaria.svg.png', 99, '2025-03-25 08:34:58'),
(7, 'Chypre', 'Chypre, en forme longue la République de Chypre (en grec moderne : Κύπρος – Kýpros et Δημοκρατία της Κύπρου – Dimokratía tis Kýprou ; en turc : Kıbrıs et Kıbrıs Cumhuriyeti), est un État situé sur l\'île de Chypre, en Asie, dans la partie orientale de la mer Méditerranée, dans le bassin levantin. Bien que Chypre soit géographiquement proche de la région du Proche-Orient, le pays est politiquement rattaché à l\'Europe et est membre de l’Union européenne (UE).', 'https://upload.wikimedia.org/wikipedia/commons/thumb/d/d4/Flag_of_Cyprus.svg/1280px-Flag_of_Cyprus.svg.png', 100, '2025-03-25 08:35:35'),
(8, 'Croatie', 'La Croatie, en forme longue la République de Croatie (en croate : Hrvatska et Republika Hrvatska), est un pays d\'Europe centrale et du Sud. Sa capitale est Zagreb, la ville la plus peuplée du pays. Comptant environ quatre millions d\'habitants, la Croatie est membre de l\'Union européenne depuis le 1ᵉʳ juillet 2013, de l\'OMC depuis 2000 et de l\'OTAN depuis 2009. Elle a intégré la zone euro et l\'espace Schengen le 1ᵉʳ janvier 2023 et a rejoint pleinement l\'Espace économique européen le 19 février 2025.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/1b/Flag_of_Croatia.svg/1920px-Flag_of_Croatia.svg.png', 99, '2025-03-25 08:36:08'),
(9, 'Danemark', 'Le Danemark, en forme longue le Royaume de Danemark (en danois : Danmark /ˈtænmɑk/ et Kongeriget Danmark), est un pays d’Europe du Nord et de Scandinavie. Son territoire métropolitain est situé au sud de la Norvège, dont il est séparé par le Skagerrak, au sud-sud-ouest de la Suède, avec le Cattégat comme frontière naturelle, et au nord de l\'Allemagne, seul pays avec lequel il partage une frontière terrestre, en dehors du Canada depuis la résolution du conflit sur l\'île Hans. Sa capitale et plus grande ville est Copenhague.', 'https://upload.wikimedia.org/wikipedia/commons/9/9c/Flag_of_Denmark.svg', 100, '2025-03-25 08:36:47'),
(10, 'Espagne', 'L\'Espagne, en forme longue le Royaume d\'Espagne (en espagnol : España et Reino de España), est un État souverain transcontinental d\'Europe du Sud-Ouest, occupant la majeure partie de la péninsule Ibérique. Le pays a une superficie de 504 030 km² et une population de 48 millions d\'habitants.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/9/9a/Flag_of_Spain.svg/1280px-Flag_of_Spain.svg.png', 100, '2025-03-25 08:37:30'),
(11, 'Estonie', 'L\'Estonie (en estonien : Eesti), en forme longue la République d\'Estonie (en estonien : Eesti Vabariik), est un État souverain d\'Europe du Nord dont le territoire s\'étend sur le flanc oriental et comprend près de 2 200 îles de la mer Baltique. La partie continentale possède des frontières terrestres avec la Russie à l\'est et la Lettonie au sud, tandis que l\'archipel d\'Estonie-occidentale constitue l\'essentiel de la partie insulaire du pays.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/8f/Flag_of_Estonia.svg/1280px-Flag_of_Estonia.svg.png', 100, '2025-03-25 08:38:07'),
(12, 'Finlande', 'La Finlande (en finnois : Suomi, prononciation ; en suédois : Finland), en forme longue la République de Finlande (en finnois : Suomen tasavalta, en suédois : Republiken Finland), est un État souverain d\'Europe du Nord dont le territoire s\'étend sur le flanc est et comprend 100 000 îles de la mer Baltique. La partie continentale possède près de 200 000 lacs et partage ses frontières terrestres avec la Russie à l\'est, ainsi qu\'avec la Suède et la Norvège au nord, tandis que les archipels de Turku et Åland constituent l\'essentiel de la partie insulaire du pays.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/bc/Flag_of_Finland.svg/langfr-1280px-Flag_of_Finland.svg.png', 99, '2025-03-25 08:38:44'),
(13, 'Grèce', 'La Grèce, en forme longue la République hellénique (en grec : Ελλάδα / Elláda /eˈlaða/, en forme longue Ελληνική Δημοκρατία / Ellinikí Dimokratía [Démocratie hellénique] ; en grec ancien et en katharévousa Ἑλλάς / Hellás), est un pays d’Europe du Sud et des Balkans. On la désigne parfois par le terme Hellade.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Flag_of_Greece.svg/1280px-Flag_of_Greece.svg.png', 100, '2025-03-25 08:47:10'),
(14, 'Hongrie', 'La Hongrie (en hongrois : Magyarország, /ˈmɒɟɒɾoɾsaːg/ Écouter) est une république constitutionnelle unitaire située dans le sud-est de l\'Europe centrale, aux confins de l\'Europe de l\'Est et de l\'Europe du Sud-Est. Elle a pour capitale Budapest, pour langue officielle le hongrois et pour monnaie le forint. Son drapeau est constitué de trois bandes horizontales, rouge, blanche et verte, et son hymne national est le Himnusz. D\'une superficie de 93 030 km², elle s\'étend sur 250 km du nord au sud et 524 km d\'est en ouest. Elle possède 2 009 km de frontières, avec l\'Autriche à l\'ouest, la Slovénie et la Croatie au sud-ouest, la Serbie au sud, la Roumanie au sud-est, l\'Ukraine au nord-est et la Slovaquie au nord.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/c1/Flag_of_Hungary.svg/1920px-Flag_of_Hungary.svg.png', 99, '2025-03-25 08:49:51'),
(15, 'Irlande', 'L\'Irlande (/iʁ.lɑ̃d/ Écouter ; en irlandais : Éire /ˈeːɾʲə/ Écouter ; en anglais : Ireland /ˈaɪəɹ.lənd/ Écouter), également connue sous le nom de République d\'Irlande (en irlandais : *Poblacht na hÉireann ; en anglais : Republic of Ireland), est un État souverain d\'Europe de l\'Ouest, ou, selon certaines définitions, du Nord. Il est constitué de vingt-six des trente-deux comtés de l\'île d\'Irlande. La capitale et la plus grande ville est Dublin, située dans l\'est de l\'île.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/45/Flag_of_Ireland.svg/1920px-Flag_of_Ireland.svg.png', 100, '2025-03-25 08:50:34'),
(16, 'Italie', 'L\'Italie (/itali/ Écouter ; en italien : Italia /iˈtaːlja/ Écouter), en forme longue la République italienne (en italien : Repubblica Italiana /reˈpubblika itaˈljaːna/), est un État souverain d\'Europe du Sud. Son territoire comprend une partie continentale, une péninsule située au centre de la mer Méditerranée, ainsi qu\'une partie insulaire constituée par les deux plus grandes îles de cette mer, la Sicile et la Sardaigne, et d\'autres îles plus petites. Elle est rattachée au reste du continent par le massif des Alpes. Le territoire italien correspond approximativement à la région géographique homonyme. Le pays possède des frontières terrestres avec la France, la Suisse, l\'Autriche et la Slovénie, ainsi que des frontières internes avec Saint-Marin et le Vatican. En 2024, l\'Italie compte 58 989 749 habitants et a une superficie de 302 073 km².', 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/03/Flag_of_Italy.svg/1280px-Flag_of_Italy.svg.png', 100, '2025-03-25 08:55:56'),
(17, 'Lettonie', 'La Lettonie (en letton : Latvija), en forme longue la République de Lettonie (en letton : Latvijas Republika), est un État souverain d\'Europe du Nord dont le territoire s\'étend sur le flanc oriental de la mer Baltique. Le territoire possède des frontières terrestres avec la Russie et la Biélorussie à l\'est, et est entouré par l\'Estonie au nord et la Lituanie au sud.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/84/Flag_of_Latvia.svg/1920px-Flag_of_Latvia.svg.png', 99, '2025-03-25 08:56:35'),
(18, 'Lituanie', 'La Lituanie (en lituanien : Lietuva), en forme longue la République de Lituanie (Lietuvos Respublika), est un État souverain d\'Europe du Nord dont le territoire s\'étend sur le flanc oriental de la mer Baltique. Le territoire possède des frontières terrestres avec la Biélorussie à l\'est, la Lettonie au nord, ainsi que la Pologne et la Russie (exclave de Kaliningrad) au sud.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/11/Flag_of_Lithuania.svg/1920px-Flag_of_Lithuania.svg.png', 99, '2025-03-25 08:57:11'),
(19, 'Luxembourg', 'Le Luxembourg, en forme longue le Grand-Duché de Luxembourg (ou grand-duché de Luxembourg) (en luxembourgeois : Lëtzebuerg Écouter et Groussherzogtum Lëtzebuerg, en allemand : Luxemburg et Großherzogtum Luxemburg), est un pays d\'Europe de l\'Ouest sans accès à la mer. Il est bordé par la Belgique à l\'ouest et au nord, l\'Allemagne à l\'est, et la France au sud. Il comprend deux régions principales : l\'Oesling (Éislek en luxembourgeois) au nord, qui fait partie du massif de l\'Ardenne, et le Gutland au sud, prolongement de la Lorraine au sens géologique du terme. Le Luxembourg compte 672 050 habitants au 1er janvier 2024 et s\'étend sur 2 586 km², ce qui en fait l\'une des plus petites nations souveraines d\'Europe.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/d/da/Flag_of_Luxembourg.svg/1920px-Flag_of_Luxembourg.svg.png', 100, '2025-03-25 08:57:54'),
(20, 'Malte', 'Malte (en maltais : Malta ; en anglais : Malta), en forme longue la république de Malte[8] (en maltais : Repubblika ta\' Malta ; en anglais : Republic of Malta), est un État insulaire d\'Europe du Sud situé au milieu de la Méditerranée, à 93 kilomètres au sud de la Sicile. Il est constitué d\'un archipel de huit îles, dont quatre sont habitées, et de plusieurs îlots et rochers. La capitale du pays, établie sur l\'île de Malte, est La Valette et sa plus grande ville est Birkirkara.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/73/Flag_of_Malta.svg/1280px-Flag_of_Malta.svg.png', 100, '2025-03-25 08:58:24'),
(21, 'Pays-Bas', 'Les Pays-Bas (en néerlandais : Nederland), en forme longue le Royaume des Pays-Bas (Koninkrijk der Nederlanden), parfois appelé Hollande par métonymie, sont un pays transcontinental dont le territoire continental est situé en Europe de l\'Ouest (ou, selon certaines interprétations, en Europe du Nord).', 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/20/Flag_of_the_Netherlands.svg/1280px-Flag_of_the_Netherlands.svg.png', 99, '2025-03-25 08:59:07'),
(22, 'Pologne', 'La Pologne, en forme longue République de Pologne (en polonais : Polska ; [forme longue] Rzeczpospolita Polska), est un État d\'Europe centrale, frontalier avec l\'Allemagne à l\'ouest, la Tchéquie au sud-ouest, la Slovaquie au sud, l\'Ukraine à l\'est-sud-est, la Biélorussie à l\'est-nord-est, et enfin l\'enclave russe de Kaliningrad et la Lituanie au nord-est.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/12/Flag_of_Poland.svg/1280px-Flag_of_Poland.svg.png', 100, '2025-03-25 08:59:47'),
(23, 'Portugal', 'Le Portugal, en forme longue la République portugaise (en portugais : República Portuguesa), est un pays d\'Europe du Sud, membre de l\'Union européenne, situé dans l\'Ouest de la péninsule Ibérique. Délimité au nord et à l\'est par l\'Espagne, puis au sud et à l\'ouest par l\'océan Atlantique, ce pays est le plus occidental de l\'Europe continentale. Fondé en 1143, c\'est le plus vieil État-nation d\'Europe, et ses frontières terrestres internationales, établies au milieu du XIIIe siècle, sont parmi les plus anciennes encore en vigueur en Europe et dans le monde.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Flag_of_Portugal.svg/1280px-Flag_of_Portugal.svg.png', 100, '2025-03-25 09:00:17'),
(24, 'République tchèque', 'La Tchéquie, en forme longue la République tchèque (en tchèque : Česko /ˈt͡ʃɛskɔ/ Écouter et Česká republika /ˈt͡ʃɛskaː ˈrɛpublɪka/ Écouter), est un pays d\'Europe centrale sans accès à la mer, entouré par la Pologne au nord-est, l\'Allemagne au nord-ouest et à l\'ouest, l\'Autriche au sud, et la Slovaquie à l\'est-sud-est.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/cb/Flag_of_the_Czech_Republic.svg/1280px-Flag_of_the_Czech_Republic.svg.png', 100, '2025-03-25 09:00:47'),
(25, 'Roumanie', 'La Roumanie (en roumain : România) est un pays de l\'Est de l\'Europe, partagé entre l\'Europe centrale, orientale et du Sud-Est. C’est le sixième pays le plus peuplé de l\'Union européenne et le douzième pays le plus grand par sa superficie totale. La géographie du pays est structurée par les Carpates, le Danube et le littoral de la mer Noire. La Roumanie partage des frontières avec la Hongrie, l\'Ukraine, la Moldavie, la Bulgarie et la Serbie.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/73/Flag_of_Romania.svg/1280px-Flag_of_Romania.svg.png', 100, '2025-03-25 09:01:20'),
(26, 'Slovaquie', 'La Slovaquie, en forme longue la République slovaque (en slovaque : Slovensko et Slovenská republika), est un pays situé en Europe centrale, au cœur de l\'Europe continentale. Elle est membre de l\'Union européenne depuis 2004. Ses pays frontaliers sont la Pologne au nord, l\'Ukraine à l\'est, la Hongrie au sud, l\'Autriche à l\'ouest et la Tchéquie à l\'ouest-nord-ouest. Cœur de la Grande-Moravie, la Slovaquie fit partie du royaume de Hongrie à partir du XIe siècle. Du 28 octobre 1918 au 21 mars 1939, puis du 4 avril 1945 au 31 décembre 1992, elle a, avec la Tchéquie, fait partie de la Tchécoslovaquie. De 1939 à 1945, une République slovaque, partiellement démembrée par la Hongrie, fut constituée sous le Troisième Reich.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e6/Flag_of_Slovakia.svg/165px-Flag_of_Slovakia.svg.png', 100, '2025-03-25 09:01:54'),
(27, 'Slovénie', 'La Slovénie, en forme longue la République de Slovénie (en slovène : Slovenija et Republika Slovenija), est un pays d’Europe centrale, au carrefour des principales cultures européennes. Sa capitale est Ljubljana. Le pays partage ses frontières avec l\'Italie à l\'ouest, l\'Autriche au nord, la Hongrie à l\'est-nord-est et la Croatie au sud-est. La Slovénie dispose, au sud-ouest, d\'un littoral d\'une quarantaine de kilomètres sur la mer Adriatique. Comptant environ deux millions d\'habitants, la Slovénie est un État membre de l\'OTAN depuis le 29 mars 2004 et de l\'Union européenne depuis le 1er mai 2004.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/f0/Flag_of_Slovenia.svg/1920px-Flag_of_Slovenia.svg.png', 100, '2025-03-25 09:02:21'),
(28, 'Suède', 'La Suède (en suédois : Sverige /ˈsvæ̌rjɛ/) — en forme longue le Royaume de Suède (Konungariket Sverige /ˈkôːnɵŋaˌriːkɛt ˈsvæ̌rjɛ/) — est un pays d\'Europe du Nord et de Scandinavie. Sa capitale est Stockholm, ses citoyens sont les Suédois et Suédoises, et sa langue officielle et majoritaire est le suédois. Le finnois et le sami sont aussi parlés, principalement dans le nord du pays. Les variations régionales sont fréquentes.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/4c/Flag_of_Sweden.svg/1280px-Flag_of_Sweden.svg.png', 100, '2025-03-25 09:02:52');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) DEFAULT 0,
  `last_card_draw` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `is_admin`, `last_card_draw`, `created_at`) VALUES
(5, 'Uncluny', '$2y$10$EZ1PDXmBxjvOP6Ff0YxjxulrNkeSG695qX3clUKK.W7S2UhckxS5S', 1, '2025-04-04 12:58:16', '2025-04-04 11:50:21'),
(6, 'jesuisuntest', '$2y$10$9H8Iy4KXD3hajDYG9iwpwebQToEpD6UrRMQiAmv6jZIwKcEYBCcSa', 0, NULL, '2025-04-04 11:51:40');

-- --------------------------------------------------------

--
-- Structure de la table `user_cards`
--

CREATE TABLE `user_cards` (
  `user_id` int(11) NOT NULL,
  `card_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user_cards`
--

INSERT INTO `user_cards` (`user_id`, `card_id`, `quantity`) VALUES
(5, 3, 1),
(5, 6, 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `cards`
--
ALTER TABLE `cards`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Index pour la table `user_cards`
--
ALTER TABLE `user_cards`
  ADD PRIMARY KEY (`user_id`,`card_id`),
  ADD KEY `card_id` (`card_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `cards`
--
ALTER TABLE `cards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `user_cards`
--
ALTER TABLE `user_cards`
  ADD CONSTRAINT `user_cards_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_cards_ibfk_2` FOREIGN KEY (`card_id`) REFERENCES `cards` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
