-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Feb 05, 2026 alle 18:44
-- Versione del server: 10.4.32-MariaDB
-- Versione PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `studybo2`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `adattocdl`
--

CREATE TABLE `adattocdl` (
  `idcdl` int(5) NOT NULL,
  `idadatto` int(5) NOT NULL,
  `descrizioneadatto` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `adattocdl`
--

INSERT INTO `adattocdl` (`idcdl`, `idadatto`, `descrizioneadatto`) VALUES
(1, 1, 'A chi vuole imparare a progettare e sviluppare software'),
(1, 2, 'A chi ama risolvere problemi con logica e metodo'),
(1, 3, 'A chi è curioso di reti, dati e sistemi'),
(1, 4, 'A chi vuole un percorso tecnico con basi solide'),
(2, 1, 'A chi è curioso del mondo medico e scientifico'),
(2, 2, 'A chi vuole capire come funzionano strumenti e sensori'),
(2, 3, 'A chi ama unire teoria e applicazioni pratiche'),
(2, 4, 'A chi vuole lavorare tra tecnologia e salute'),
(3, 1, 'A chi è curioso di circuiti e componenti'),
(3, 2, 'A chi ama la matematica applicata'),
(3, 3, 'A chi vuole capire come funziona l’hardware'),
(3, 4, 'A chi preferisce anche attività pratiche/laboratorio'),
(4, 1, 'A chi ama progettare e ragionare sugli spazi'),
(4, 2, 'A chi vuole un percorso creativo ma strutturato'),
(4, 3, 'A chi è interessato a città, edifici e territorio'),
(4, 4, 'A chi vuole imparare metodo e comunicazione del progetto'),
(5, 1, 'A chi vuole lavorare su progetti “clinici” e tecnologici'),
(5, 2, 'A chi è interessato a R&D e innovazione'),
(5, 3, 'A chi vuole approfondire segnali, immagini e dispositivi'),
(5, 4, 'A chi punta a una tesi più tecnica o sperimentale');

-- --------------------------------------------------------

--
-- Struttura della tabella `adesione`
--

CREATE TABLE `adesione` (
  `idcdl` int(11) NOT NULL,
  `idesame` int(11) NOT NULL,
  `idstudygroup` int(11) NOT NULL,
  `username` char(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `adesione`
--

INSERT INTO `adesione` (`idcdl`, `idesame`, `idstudygroup`, `username`) VALUES
(1, 9, 2, 'gianni.gregori'),
(3, 47, 3, 'gianni.gregori');

-- --------------------------------------------------------

--
-- Struttura della tabella `argomento`
--

CREATE TABLE `argomento` (
  `idcdl` int(5) NOT NULL,
  `idesame` int(5) NOT NULL,
  `idargomento` int(5) NOT NULL,
  `descrizioneargomento` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `argomento`
--

INSERT INTO `argomento` (`idcdl`, `idesame`, `idargomento`, `descrizioneargomento`) VALUES
(1, 3, 1, 'Modello relazionale'),
(1, 3, 2, 'SQL (basi)'),
(1, 3, 3, 'Progettazione concettuale'),
(1, 4, 1, 'Limiti e derivate'),
(1, 4, 2, 'Integrali'),
(1, 4, 3, 'Esercizi svolti'),
(1, 5, 1, 'Fondamenti, strutture dati'),
(1, 5, 2, 'Esercizi e progetti'),
(1, 5, 3, 'Debug e ragionamento'),
(1, 8, 1, 'Spazi vettoriali'),
(1, 8, 2, 'Matrici e diagonalizzazione'),
(1, 8, 3, 'Formulari e schemi'),
(1, 11, 1, 'Processi e scheduling'),
(1, 11, 2, 'Memoria e file system'),
(1, 11, 3, 'Concetti chiave'),
(1, 15, 1, 'Modello ISO/OSI e TCP/IP'),
(1, 15, 2, 'Indirizzamento e servizi'),
(1, 15, 3, 'Esercizi e casi'),
(2, 18, 1, 'Meccanica e dinamica'),
(2, 18, 2, 'Lavoro ed energia'),
(2, 18, 3, 'Esercizi svolti'),
(2, 24, 1, 'Nomenclatura e reazioni'),
(2, 24, 2, 'Soluzioni e concentrazioni'),
(2, 24, 3, 'Ripasso veloce'),
(2, 25, 1, 'Acquisizione e filtraggio'),
(2, 25, 2, 'Analisi di segnali (ECG, ecc.)'),
(2, 25, 3, 'Strumentazione di base'),
(2, 27, 1, 'Funzionamento dei sistemi'),
(2, 27, 2, 'Regolazione e feedback'),
(2, 27, 3, 'Appunti e riassunti'),
(2, 33, 1, 'Apparati principali'),
(2, 33, 2, 'Terminologia'),
(2, 33, 3, 'Schemi e mappe'),
(2, 34, 1, 'Materiali e proprietà'),
(2, 34, 2, 'Compatibilità e applicazioni'),
(2, 34, 3, 'Casi d’uso'),
(3, 46, 1, 'Strumenti e incertezze'),
(3, 46, 2, 'Calibrazione'),
(3, 46, 3, 'Laboratorio'),
(3, 47, 1, 'Trasformate (basi)'),
(3, 47, 2, 'Risposta di sistemi'),
(3, 47, 3, 'Esercizi e formulari'),
(3, 48, 1, 'Leggi di Kirchhoff'),
(3, 48, 2, 'Reti e metodi di analisi'),
(3, 48, 3, 'Esercizi svolti'),
(3, 49, 1, 'CPU, memoria e bus'),
(3, 49, 2, 'Cache e pipeline'),
(3, 49, 3, 'Concetti hardware'),
(3, 53, 1, 'Diodi e transistor (basi)'),
(3, 53, 2, 'Amplificatori operazionali'),
(3, 53, 3, 'Analisi circuiti'),
(3, 56, 1, 'Porte logiche e combinatoria'),
(3, 56, 2, 'Sequenziali e registri'),
(3, 56, 3, 'Esercizi e progetti'),
(4, 58, 1, 'Norme grafiche'),
(4, 58, 2, 'Proiezioni e sezioni'),
(4, 58, 3, 'Esempi ed esercizi'),
(4, 60, 1, 'Modellazione di base'),
(4, 60, 2, 'Tavole e impaginazione'),
(4, 60, 3, 'Presentazione del progetto'),
(4, 62, 1, 'Città e territorio'),
(4, 62, 2, 'Piani e strumenti'),
(4, 62, 3, 'Analisi e casi studio'),
(4, 63, 1, 'Materiali e prestazioni'),
(4, 63, 2, 'Stratigrafie e dettagli'),
(4, 63, 3, 'Scelte progettuali'),
(4, 64, 1, 'Periodi e stili'),
(4, 64, 2, 'Opere e autori'),
(4, 64, 3, 'Schemi e riassunti'),
(4, 70, 1, 'Concept e idea progettuale'),
(4, 70, 2, 'Spazi e funzioni'),
(4, 70, 3, 'Revisioni e consegne'),
(5, 81, 1, 'Materiali per impianti'),
(5, 81, 2, 'Interazione con tessuti'),
(5, 81, 3, 'Applicazioni e casi studio'),
(5, 83, 1, 'Progettazione e requisiti'),
(5, 83, 2, 'Affidabilità e sicurezza'),
(5, 83, 3, 'Validazione e test'),
(5, 84, 1, 'Sensori e acquisizione dati'),
(5, 84, 2, 'Calibrazione e misure'),
(5, 84, 3, 'Prototipi e laboratorio'),
(5, 85, 1, 'Filtraggio e analisi nel tempo/frequenza'),
(5, 85, 2, 'Rumore e artefatti'),
(5, 85, 3, 'Applicazioni (ECG/EEG, ecc.)'),
(5, 88, 1, 'Filtri e trasformazioni'),
(5, 88, 2, 'Segmentazione (basi)'),
(5, 88, 3, 'Valutazione e metriche');

-- --------------------------------------------------------

--
-- Struttura della tabella `cdl`
--

CREATE TABLE `cdl` (
  `idcdl` int(11) NOT NULL,
  `nomecdl` char(200) NOT NULL,
  `sede` char(30) NOT NULL,
  `img` char(100) NOT NULL,
  `durata` int(11) NOT NULL,
  `descrizionecdl` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `cdl`
--

INSERT INTO `cdl` (`idcdl`, `nomecdl`, `sede`, `img`, `durata`, `descrizionecdl`) VALUES
(1, 'Laurea in Ingegneria e Scienze Informatiche', 'Cesena', '5d04fdba1e2cf92a.jpg', 3, 'Il corso di laurea in Ingegneria e Scienze Informatiche ti dà basi solide per progettare e sviluppare software, capire come funzionano sistemi e reti, e lavorare con dati e applicazioni reali. È un percorso tecnico, pratico e molto richiesto, con tante opportunità di crescita anche verso la magistrale.'),
(2, 'Laurea in Ingegneria Biomedica', 'Cesena', 'eace393bf58c9daa.png', 3, 'Il corso di laurea in Ingegneria Biomedica unisce basi di ingegneria con scienze della vita, per progettare tecnologie utili alla salute: dispositivi medici, sensori, analisi di segnali, biomateriali e strumenti per la diagnosi. È un percorso adatto a chi vuole applicare la tecnologia al mondo medico e al benessere delle persone.'),
(3, 'Laurea in Ingegneria Elettronica', 'Cesena', 'a5f247e7fe64bc84.jpg', 3, 'Il corso di laurea in Ingegneria Elettronica unisce matematica e fisica con lo studio di circuiti, segnali e sistemi, elettronica analogica e digitale. Impari a progettare e analizzare dispositivi e sistemi elettronici, dalla teoria fino alle applicazioni pratiche in laboratorio.'),
(4, 'Laurea Magistrale a CU in Architettura', 'Cesena', '17ada64eecf476fb.png', 5, 'Il corso di laurea in Architettura unisce progettazione, cultura del progetto e competenze tecniche. Si lavora su disegno, storia dell’architettura, urbanistica e laboratori progettuali, con un percorso che combina creatività e metodo. È adatto a chi vuole progettare spazi, edifici e soluzioni che migliorano la vita quotidiana delle persone.'),
(5, 'Laurea Magistrale in Ingegneria Biomedica', 'Cesena', '254b7813fa73221f.jpg', 2, 'La Laurea Magistrale in Ingegneria Biomedica approfondisce la progettazione e l’analisi di tecnologie per la salute: dispositivi medici, strumentazione, elaborazione di segnali e immagini, biomateriali e sistemi per il supporto clinico. È pensata per chi vuole lavorare su progetti più complessi, con un taglio più avanzato e orientato a ricerca e R&D.');

-- --------------------------------------------------------

--
-- Struttura della tabella `cosasistudia`
--

CREATE TABLE `cosasistudia` (
  `idcdl` int(5) NOT NULL,
  `idstudia` int(5) NOT NULL,
  `descrizionestudia` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `cosasistudia`
--

INSERT INTO `cosasistudia` (`idcdl`, `idstudia`, `descrizionestudia`) VALUES
(1, 1, 'Programmazione e strutture dati'),
(1, 2, 'Matematica e algebra per l’ingegneria'),
(1, 3, 'Sistemi operativi e architettura degli elaboratori'),
(1, 4, 'Basi di dati e progettazione'),
(1, 5, 'Reti e servizi di rete (fondamenti)'),
(2, 1, 'Basi di matematica, fisica e informatica'),
(2, 2, 'Chimica e biomateriali'),
(2, 3, 'Anatomia e fisiologia (fondamenti)'),
(2, 4, 'Segnali biomedici e strumentazione'),
(2, 5, 'Progettazione di sistemi e dispositivi'),
(3, 1, 'Matematica e fisica per l’ingegneria'),
(3, 2, 'Circuiti elettrici e reti'),
(3, 3, 'Elettronica analogica e digitale'),
(3, 4, 'Segnali e sistemi'),
(3, 5, 'Misure e strumentazione'),
(4, 1, 'Progettazione architettonica'),
(4, 2, 'Disegno tecnico e rappresentazione'),
(4, 3, 'Storia dell’architettura'),
(4, 4, 'Urbanistica e pianificazione'),
(4, 5, 'Tecnologia dei materiali e del costruire'),
(5, 1, 'Progettazione avanzata di dispositivi e sistemi biomedicali'),
(5, 2, 'Segnali e immagini biomediche (analisi e metodi)'),
(5, 3, 'Biomateriali e tecnologie per impianti'),
(5, 4, 'Strumentazione e sensoristica'),
(5, 5, 'Metodi per validazione e sperimentazione');

-- --------------------------------------------------------

--
-- Struttura della tabella `destnotificapreferenza`
--

CREATE TABLE `destnotificapreferenza` (
  `idcdl` int(3) NOT NULL,
  `idesame` int(3) NOT NULL,
  `idstudygroup` int(3) NOT NULL,
  `idnotifica` int(11) NOT NULL,
  `username` char(30) NOT NULL,
  `letta` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `destnotificarisorsa`
--

CREATE TABLE `destnotificarisorsa` (
  `idcdl` int(2) NOT NULL,
  `idesame` int(3) NOT NULL,
  `idstudygroup` int(3) NOT NULL,
  `idrisorsa` int(3) NOT NULL,
  `idnotifica` int(3) NOT NULL,
  `username` char(30) NOT NULL,
  `letta` tinyint(1) NOT NULL,
  `commento` char(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `destnotificavarsg`
--

CREATE TABLE `destnotificavarsg` (
  `idcdl` int(3) NOT NULL,
  `idesame` int(3) NOT NULL,
  `idstudygroup` int(11) NOT NULL,
  `idnotifica` int(11) NOT NULL,
  `username` char(30) NOT NULL,
  `letta` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `esame`
--

CREATE TABLE `esame` (
  `idcdl` int(11) NOT NULL,
  `idesame` int(11) NOT NULL,
  `nomeesame` char(100) NOT NULL,
  `annoesame` int(11) NOT NULL,
  `imgesame` char(100) NOT NULL,
  `principale` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `esame`
--

INSERT INTO `esame` (`idcdl`, `idesame`, `nomeesame`, `annoesame`, `imgesame`, `principale`) VALUES
(1, 1, 'Programmazione ad oggetti', 2, '4365c24451776f31.png', 0),
(1, 2, 'Tecnologie Web', 3, 'e166813751fc199c.jpg', 0),
(1, 3, 'Base di dati', 2, '1b0be76340d6dc28.png', 1),
(1, 4, 'Analisi Matematica', 1, 'e9cbaa8e14c5ea03.jpg', 1),
(1, 5, 'Programmazione', 1, '4b2b3d38a30acd17.jpg', 1),
(1, 6, 'Architettura degli elaboratori', 1, 'c4ed417bb2642e1c.jpg', 0),
(1, 7, 'Algoritmi e strutture dati', 1, '3e2efe541f74886c.jpg', 0),
(1, 8, 'Algebra e geometria', 1, 'a4efa14aa6aa6a60.jpg', 1),
(1, 9, 'Metodi numerici', 2, 'e47d2080e1c33823.jpg', 0),
(1, 10, 'Matematica discreta e probabilità', 2, '93a5f41115843da2.jpg', 0),
(1, 11, 'Sistemi Operativi', 2, '142647e584ba5140.jpg', 1),
(1, 12, 'Fisica', 2, '2ce5f9b72c54da17.jpg', 0),
(1, 13, 'Programmazione di reti', 2, '4995408b618e9935.jpg', 0),
(1, 14, 'Ricerca operativa', 3, 'a85e4f0cce222a3d.png', 0),
(1, 15, 'Reti di telecomunicazioni', 3, 'b79e7a4f1c5686a5.jpg', 0),
(1, 16, 'Ingegneria del software', 3, '9cb782466b98676d.jpg', 0),
(2, 18, 'Fisica Generale A', 1, '', 1),
(2, 19, 'Fisica Generale B', 1, '', 0),
(2, 20, 'Geometria e Algebra', 1, '', 0),
(2, 21, 'Analisi Matematica B', 1, '3916602b1c919584.jpg', 0),
(2, 22, 'Fondamenti di Informatica', 1, '', 0),
(2, 23, 'Laboratorio di Bioingegneria Informatica', 1, '', 0),
(2, 24, 'Fondamenti di Chimica', 1, '', 1),
(2, 25, 'Elaborazione dei Segnali', 2, '', 1),
(2, 26, 'Fisica Tecnica', 2, '', 0),
(2, 27, 'Fisiologia', 2, '', 1),
(2, 28, 'Sistemi e Circuiti Elettrici Lineari', 2, '', 0),
(2, 29, 'Bioingegneria', 2, '13d067a7a3a33158.jpg', 0),
(2, 30, 'Calcolatori Elettronici', 2, '50a1f89130fa1275.jpg', 0),
(2, 31, 'Controlli Automatici', 2, 'f26e0d695ee6600f.jpg', 0),
(2, 32, 'Elettronica', 2, '', 0),
(2, 33, 'Fondamenti di Biomeccanica', 3, '', 1),
(2, 34, 'Meccanica dei Biomateriali e delle Strutture', 3, '', 1),
(2, 35, 'Strumentazione per l\'Ingegneria Biomedica', 3, '', 0),
(2, 36, 'Economia e Organizzazione Aziendale', 3, '', 0),
(2, 37, 'Ingegneria Clinica e Informatica Medica', 3, '', 0),
(2, 92, 'Analisi Matematica A', 1, '621415b53ee52c49.jpg', 0),
(3, 38, 'Analisi Matematica A', 1, '', 0),
(3, 39, 'Fisica Generale A', 1, '', 0),
(3, 40, 'Fisica Generale B', 1, '', 0),
(3, 41, 'Fondamenti di Chimica', 1, '', 0),
(3, 42, 'Geometria e Algebra', 1, '', 0),
(3, 43, 'Analisi Matematica B', 1, '', 0),
(3, 44, 'Fondamenti di Informatica A', 1, '', 0),
(3, 45, 'Laboratori di Informatica', 1, '', 0),
(3, 46, 'Algoritmi di Ottimizzazione', 2, '', 1),
(3, 47, 'Elaborazione dei Segnali', 2, '', 1),
(3, 48, 'Sistemi e Circuiti Elettrici Lineari', 2, '', 1),
(3, 49, 'Calcolatori Elettronici', 2, '', 1),
(3, 50, 'Controlli Automatici', 2, '', 0),
(3, 51, 'Conversione Elettromeccanica dell\'Energia', 2, '', 0),
(3, 52, 'Laboratorio di Ingegneria dell\'Informazione', 2, '', 0),
(3, 53, 'Elettronica', 2, '', 1),
(3, 54, 'Campi Elettromagnetici', 3, '', 0),
(3, 55, 'Comunicazioni Digitali e Internet', 3, '', 0),
(3, 56, 'Elettronica dei Sistemi Digitali', 3, '', 1),
(3, 57, 'Progetto di Circuiti Elettronici', 3, '', 0),
(4, 58, 'Disegno dell\'Architettura', 1, '', 1),
(4, 59, 'Laboratorio di Informatica Grafica', 1, '', 0),
(4, 60, 'Rappresentazione Tecnica', 1, '', 1),
(4, 61, 'Matematica', 1, '', 0),
(4, 62, 'Elementi di Urbanistica', 1, '', 1),
(4, 63, 'Materiali e Progettazione di Elementi Costruttivi', 1, '', 1),
(4, 64, 'Storia dell\'Architettura 1', 1, '', 1),
(4, 65, 'Architettura e Composizione Architettonica I', 1, '', 0),
(4, 66, 'Caratteri Distributivi degli Edifici I', 1, '', 0),
(4, 67, 'Disegno della città e del paesaggio', 1, '', 0),
(4, 68, 'Fisica Tecnica Ambientale I', 2, '', 0),
(4, 69, 'Progettazione Ambientale', 2, '', 0),
(4, 70, 'Progettazione Tecnologica', 2, '', 1),
(4, 71, 'Disegno Architettonico e Analisi Grafica', 2, '', 0),
(4, 72, 'Fondamenti di Comunicazione Grafica', 2, '', 0),
(4, 73, 'Architettura degli Interni I', 2, '', 0),
(4, 74, 'Architettura e Composizione Architettonica II', 2, '', 0),
(4, 75, 'Caratteri Distributivi degli Edifici II', 2, '', 0),
(4, 76, 'Storia dell\'Architettura 2', 2, '', 0),
(4, 77, 'Strutture e Statica nell\'Architettura', 2, '', 0),
(5, 78, 'Bioelectromagnetism', 1, '', 0),
(5, 79, 'Biostatistics', 1, '', 0),
(5, 80, 'Laboratory of Wearables and Mobile Health', 1, '', 0),
(5, 81, 'Neurophysiology', 1, '', 1),
(5, 82, 'Numerical Analysis and Differential Equations', 1, '', 0),
(5, 83, 'Biological System Modeling', 1, '', 1),
(5, 84, 'Biomedical Measurements and Instrumentation', 1, '', 1),
(5, 85, 'Biomedical Signal Processing', 1, '', 1),
(5, 86, 'Machine Learning for Bioengineering', 1, '', 0),
(5, 87, 'Advanced Techniques for EEG Processing', 2, '', 0),
(5, 88, 'Computational Neuroimaging', 2, '', 1),
(5, 89, 'Neural System', 2, '', 0),
(5, 90, 'Neurobotics and Neurorehabilitation', 2, '', 0),
(5, 91, 'Neuroscience and Cognition', 2, '', 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `lingua`
--

CREATE TABLE `lingua` (
  `idlingua` char(5) NOT NULL,
  `descrizionelingua` char(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `lingua`
--

INSERT INTO `lingua` (`idlingua`, `descrizionelingua`) VALUES
('EN', 'English'),
('IT', 'Italiano');

-- --------------------------------------------------------

--
-- Struttura della tabella `messaggio`
--

CREATE TABLE `messaggio` (
  `idcdl` int(3) NOT NULL,
  `idesame` int(4) NOT NULL,
  `idstudygroup` int(5) NOT NULL,
  `idmessaggio` int(5) NOT NULL,
  `username` char(30) NOT NULL,
  `testomessaggio` char(255) NOT NULL,
  `datamsg` date NOT NULL,
  `oramsg` time NOT NULL,
  `msgsegnalato` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `notificapreferenza`
--

CREATE TABLE `notificapreferenza` (
  `idcdl` int(3) NOT NULL,
  `idesame` int(3) NOT NULL,
  `idnotifica` int(3) NOT NULL,
  `idstudygroup` int(3) NOT NULL,
  `username` char(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `notificarisorsa`
--

CREATE TABLE `notificarisorsa` (
  `idcdl` int(3) NOT NULL,
  `idesame` int(3) NOT NULL,
  `idstudygroup` int(4) NOT NULL,
  `idrisorsa` int(3) NOT NULL,
  `idnotrisorsa` int(3) NOT NULL,
  `autorizzata` tinyint(1) NOT NULL,
  `lavorata` tinyint(1) NOT NULL,
  `risposta` tinyint(1) NOT NULL,
  `commento` char(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `notificavarsg`
--

CREATE TABLE `notificavarsg` (
  `idcdl` int(3) NOT NULL,
  `idesame` int(3) NOT NULL,
  `idstudygroup` int(3) NOT NULL,
  `idnotifica` int(3) NOT NULL,
  `username` char(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `preferenza`
--

CREATE TABLE `preferenza` (
  `idcdl` int(11) NOT NULL,
  `idesame` int(11) NOT NULL,
  `username` char(100) NOT NULL,
  `idpreferenza` int(11) NOT NULL,
  `luogo` char(20) NOT NULL,
  `daora` time NOT NULL,
  `aora` time NOT NULL,
  `idlingua` char(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `preferenza`
--

INSERT INTO `preferenza` (`idcdl`, `idesame`, `username`, `idpreferenza`, `luogo`, `daora`, `aora`, `idlingua`) VALUES
(1, 4, 'gianni.gregori', 1, 'Fisico', '10:00:00', '12:00:00', 'EN');

-- --------------------------------------------------------

--
-- Struttura della tabella `risorsa`
--

CREATE TABLE `risorsa` (
  `idcdl` int(2) NOT NULL,
  `idesame` int(3) NOT NULL,
  `idstudygroup` int(3) NOT NULL,
  `nomeris` char(30) NOT NULL,
  `private` tinyint(1) NOT NULL,
  `username` char(30) NOT NULL,
  `filerisorsa` char(50) NOT NULL,
  `idrisorsa` int(3) NOT NULL,
  `notifica` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `sbocchicdl`
--

CREATE TABLE `sbocchicdl` (
  `idcdl` int(5) NOT NULL,
  `idsbocchi` int(5) NOT NULL,
  `descrizionesbocchi` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `sbocchicdl`
--

INSERT INTO `sbocchicdl` (`idcdl`, `idsbocchi`, `descrizionesbocchi`) VALUES
(1, 1, 'Sviluppo software (web, app, backend)'),
(1, 2, 'Sistemi e reti (supporto tecnico, networking di base)'),
(1, 3, 'Data e basi di dati (supporto e sviluppo)'),
(1, 4, 'Cybersecurity (basi) e ruoli junior'),
(1, 5, 'Proseguimento con magistrale e specializzazione'),
(2, 1, 'Industria di dispositivi medici'),
(2, 2, 'Laboratori e aziende biomedicali'),
(2, 3, 'Ricerca e sviluppo (R&D)'),
(2, 4, 'Analisi segnali e dati in ambito clinico'),
(2, 5, 'Supporto tecnico e gestione tecnologie sanitarie'),
(3, 1, 'Progettazione elettronica (analogica/digitale)'),
(3, 2, 'Embedded e sistemi hardware/software'),
(3, 3, 'Automazione e sensori'),
(3, 4, 'Telecomunicazioni (basi e applicazioni)'),
(3, 5, 'Misure, test e qualità'),
(4, 1, 'Studi di progettazione e collaborazione professionale'),
(4, 2, 'Disegno e modellazione (CAD/3D)'),
(4, 3, 'Supporto tecnico in ambito edilizio'),
(4, 4, 'Urbanistica e analisi del territorio'),
(4, 5, 'Progettazione e gestione di interventi (in team)'),
(5, 1, 'R&D in aziende di dispositivi medicali'),
(5, 2, 'Ingegneria clinica e tecnologie sanitarie'),
(5, 3, 'Elaborazione segnali/immagini per applicazioni mediche'),
(5, 4, 'Biomateriali e progettazione protesi'),
(5, 5, 'Ricerca in laboratorio e sviluppo prototipi');

-- --------------------------------------------------------

--
-- Struttura della tabella `studygroup`
--

CREATE TABLE `studygroup` (
  `idcdl` int(11) NOT NULL,
  `idesame` int(11) NOT NULL,
  `idstudygroup` int(11) NOT NULL,
  `idlingua` char(5) NOT NULL,
  `tema` char(30) NOT NULL,
  `luogo` char(20) NOT NULL,
  `dettaglioluogo` text NOT NULL,
  `data` date NOT NULL,
  `ora` time NOT NULL,
  `amministratoresg` char(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `studygroup`
--

INSERT INTO `studygroup` (`idcdl`, `idesame`, `idstudygroup`, `idlingua`, `tema`, `luogo`, `dettaglioluogo`, `data`, `ora`, `amministratoresg`) VALUES
(1, 9, 2, 'EN', 'Python', 'Fisico', 'Via cuba', '2026-06-20', '10:00:00', ''),
(3, 47, 3, 'IT', 'Segnali', 'Fisico', 'Via cuba', '2026-06-20', '10:00:00', '');

-- --------------------------------------------------------

--
-- Struttura della tabella `user`
--

CREATE TABLE `user` (
  `username` char(100) NOT NULL,
  `cognome` char(100) NOT NULL,
  `nome` char(100) NOT NULL,
  `password` char(200) NOT NULL,
  `attivo` char(1) NOT NULL,
  `amministratore` char(1) NOT NULL,
  `imguser` char(100) NOT NULL,
  `idcdl` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `user`
--

INSERT INTO `user` (`username`, `cognome`, `nome`, `password`, `attivo`, `amministratore`, `imguser`, `idcdl`) VALUES
('ale.berti', 'Bertini', 'Alessia', '$2y$10$EszRCmjxlGCHNGWaw6BDGu6PfA6JH2DHZIpIkhotJgL7KicJL2R.u', '1', '0', '3c61a1405504f477.jpg', 4),
('Fedez', 'Morbidelli', 'Federico', '$2y$10$t1fKD.GBLt4emPtxZVXKmO.gs2owecAOMdDIMPHagkfha.r1dD7b.', '1', '0', 'f10d6a9b9ba62a19.jpg', NULL),
('francy', 'Rosso', 'Francesca', '$2y$10$lHuiS6Y4YrM9DBWZzP8BLup7DPVLqqwwnOt50n/Cpy35kq5pmqvGa', '1', '0', 'f1ba05f66b08ede6.jpg', 1),
('gianni.gregori', 'Gregori', 'Gianni', '$2y$10$rVdb/mj7zSLCg.FV50OQ3OL3O/ioFpZBeg6H19Swz9gcl.adx2Vqe', '1', '1', 'e700ce4a5b33c2b8.jpg', 1),
('mario.rossi1', 'Rossi', 'Mario', '$2y$10$AV0hNgl14XVWs6Cf1lOKWe8D5k24KAquxFaW2MI/0FaBmzeu6A12a', '1', '1', '', NULL),
('Marty', 'Gioia', 'Martina', '$2y$10$gUHfahQ6p7gZlpXNtEEs4uHrt7oGfPHPWfBTGQUlOmD5FW.7gMY6.', '1', '0', 'b86e9b0d9e43adf2.jpg', 1);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `adattocdl`
--
ALTER TABLE `adattocdl`
  ADD PRIMARY KEY (`idcdl`,`idadatto`);

--
-- Indici per le tabelle `adesione`
--
ALTER TABLE `adesione`
  ADD PRIMARY KEY (`idcdl`,`idesame`,`idstudygroup`,`username`),
  ADD KEY `FKADE_use` (`username`);

--
-- Indici per le tabelle `argomento`
--
ALTER TABLE `argomento`
  ADD PRIMARY KEY (`idcdl`,`idesame`,`idargomento`);

--
-- Indici per le tabelle `cdl`
--
ALTER TABLE `cdl`
  ADD PRIMARY KEY (`idcdl`);

--
-- Indici per le tabelle `cosasistudia`
--
ALTER TABLE `cosasistudia`
  ADD PRIMARY KEY (`idcdl`,`idstudia`);

--
-- Indici per le tabelle `destnotificapreferenza`
--
ALTER TABLE `destnotificapreferenza`
  ADD PRIMARY KEY (`idcdl`,`idesame`,`idstudygroup`,`idnotifica`,`username`);

--
-- Indici per le tabelle `destnotificarisorsa`
--
ALTER TABLE `destnotificarisorsa`
  ADD PRIMARY KEY (`idcdl`,`idesame`,`idstudygroup`,`idrisorsa`,`idnotifica`,`username`);

--
-- Indici per le tabelle `destnotificavarsg`
--
ALTER TABLE `destnotificavarsg`
  ADD PRIMARY KEY (`idcdl`,`idesame`,`idstudygroup`,`idnotifica`,`username`);

--
-- Indici per le tabelle `esame`
--
ALTER TABLE `esame`
  ADD PRIMARY KEY (`idcdl`,`idesame`);

--
-- Indici per le tabelle `lingua`
--
ALTER TABLE `lingua`
  ADD PRIMARY KEY (`idlingua`);

--
-- Indici per le tabelle `messaggio`
--
ALTER TABLE `messaggio`
  ADD PRIMARY KEY (`idcdl`,`idesame`,`idstudygroup`,`idmessaggio`);

--
-- Indici per le tabelle `notificapreferenza`
--
ALTER TABLE `notificapreferenza`
  ADD PRIMARY KEY (`idcdl`,`idesame`,`idnotifica`,`idstudygroup`);

--
-- Indici per le tabelle `notificarisorsa`
--
ALTER TABLE `notificarisorsa`
  ADD PRIMARY KEY (`idcdl`,`idesame`,`idstudygroup`,`idrisorsa`,`idnotrisorsa`);

--
-- Indici per le tabelle `notificavarsg`
--
ALTER TABLE `notificavarsg`
  ADD PRIMARY KEY (`idcdl`,`idesame`,`idstudygroup`,`idnotifica`);

--
-- Indici per le tabelle `preferenza`
--
ALTER TABLE `preferenza`
  ADD PRIMARY KEY (`idcdl`,`idesame`,`username`,`idpreferenza`) USING BTREE,
  ADD KEY `FKPRE_use` (`username`);

--
-- Indici per le tabelle `risorsa`
--
ALTER TABLE `risorsa`
  ADD PRIMARY KEY (`idcdl`,`idesame`,`idstudygroup`,`idrisorsa`);

--
-- Indici per le tabelle `sbocchicdl`
--
ALTER TABLE `sbocchicdl`
  ADD PRIMARY KEY (`idcdl`,`idsbocchi`);

--
-- Indici per le tabelle `studygroup`
--
ALTER TABLE `studygroup`
  ADD PRIMARY KEY (`idcdl`,`idesame`,`idstudygroup`),
  ADD KEY `FKINLINGUA` (`idlingua`);

--
-- Indici per le tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`username`);

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `adesione`
--
ALTER TABLE `adesione`
  ADD CONSTRAINT `FKADE_stu` FOREIGN KEY (`idcdl`,`idesame`,`idstudygroup`) REFERENCES `studygroup` (`idcdl`, `idesame`, `idstudygroup`),
  ADD CONSTRAINT `FKADE_use` FOREIGN KEY (`username`) REFERENCES `user` (`username`);

--
-- Limiti per la tabella `esame`
--
ALTER TABLE `esame`
  ADD CONSTRAINT `FKSUDDIVISIONE` FOREIGN KEY (`idcdl`) REFERENCES `cdl` (`idcdl`);

--
-- Limiti per la tabella `preferenza`
--
ALTER TABLE `preferenza`
  ADD CONSTRAINT `FKPRE_esa` FOREIGN KEY (`idcdl`,`idesame`) REFERENCES `esame` (`idcdl`, `idesame`),
  ADD CONSTRAINT `FKPRE_use` FOREIGN KEY (`username`) REFERENCES `user` (`username`);

--
-- Limiti per la tabella `studygroup`
--
ALTER TABLE `studygroup`
  ADD CONSTRAINT `FKDIVISIONE` FOREIGN KEY (`idcdl`,`idesame`) REFERENCES `esame` (`idcdl`, `idesame`),
  ADD CONSTRAINT `FKINLINGUA` FOREIGN KEY (`idlingua`) REFERENCES `lingua` (`idlingua`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
