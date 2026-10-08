CREATE TABLE IF NOT EXISTS `data_login` (
`id_login` int( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY , 
 
`photo` varchar (50) NOT NULL,
`nama_lengkap` varchar (50) NOT NULL,
`jenis_kelamin` enum('Pria','Wanita')  NOT NULL,
`alamat` text  NOT NULL,
`bio` varchar (50) NOT NULL,
`email` varchar (50) NOT NULL,
`email` varchar (50) NOT NULL 
  
 )  ENGINE=MyISAM; 
 