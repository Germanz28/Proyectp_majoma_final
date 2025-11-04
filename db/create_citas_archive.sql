CREATE TABLE IF NOT EXISTS citas_archive (
  id INT PRIMARY KEY AUTO_INCREMENT,
  original_id INT NULL,
  nombre TEXT,
  telefono VARCHAR(255),
  fecha DATE,
  hora TIME,
  servicio TEXT,
  notas TEXT,
  status VARCHAR(50),
  archived_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;