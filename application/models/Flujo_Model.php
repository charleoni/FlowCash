<?php

class Flujo_model extends CI_Model {
    public $title;
    public $content;
    public $date;

    //Cilindros CLI
    public function flujoIngreso()
    {
    	/*
			Query para el renglón de cilindros - CIL
		*/
		$resultado = $this->db->query("SELECT
                                            ID_CO, SUM(NETO_MES_REAL_L2) as valor
                                        FROM
                                            BD_BIABLE01.CGRESUMEN_CUENTA_CCOSTO
                                        WHERE
                                            LAPSO_DOC = '201806'
                                            AND (ID_CUENTA = '43252902'
                                            OR ID_CUENTA = '43252907'
                                            OR ID_CUENTA = '43950502'
                                            OR ID_CUENTA = '43900402'
                                            OR ID_CUENTA = '43950507')
                                        GROUP BY ID_CO");

        if($resultado->num_rows() > 0 )
        {
            return $resultado->result();
            //return $resultado->result_array();
        }
    }

    public function queryCuadreEbitda()
    {
        $this->db->select('LEFT(cg.ID_CUENTA, 2) CUENTA,
                            format(SUM(cg.SALDOS_INICIAL_REAL_L2), 2, 2) SALDO_INICIAL,
                            format(SUM(cg.SALDOS_DEB_REAL_L2), 2, 2) DEBITO,
                            format(SUM(cg.SALDOS_CRE_REAL_L2), 2, 2) CREDITO,
                            format(SUM(cg.SALDOS_FINAL_REAL_L2), 2, 2) NUEVO_SALDO');
        $this->db->from('CGRESUMEN_CUENTA_CCOSTO AS cg');
        $this->db->where('cg.LAPSO_DOC', '201901');
        $this->db->where('cg.ID_CUENTA >=', '4');
        $this->db->where('cg.ID_CUENTA <=', '7');
        $this->db->group_by("LEFT(cg.ID_CUENTA, 2)");  // Produces: GROUP BY title, date
        
        $query = $this->db->get();
        //echo $query->num_rows();
        //die();
        if($query->num_rows() > 0 )
        {
            return $query->result();
        }
    }
    
    public function get_Table_Excel()
    {        
        $this->load->dbutil();
        $query = $this->db->query("SELECT 
                                    LEFT(cg.ID_CUENTA, 2)CUENTA,
                                    format(SUM(cg.SALDOS_INICIAL_REAL_L2), 2, 2) SALDO_INICIAL,
                                    format(SUM(cg.SALDOS_DEB_REAL_L2), 2, 2) DEBITO,
                                    format(SUM(cg.SALDOS_CRE_REAL_L2), 2, 2) CREDITO,
                                    format(SUM(cg.SALDOS_FINAL_REAL_L2), 2, 2) NUEVO_SALDO
                                FROM
                                    CGRESUMEN_CUENTA_CCOSTO AS cg
                                WHERE
                                    cg.LAPSO_DOC = '201901'
                                        AND cg.ID_CUENTA >= '4'
                                        AND cg.ID_CUENTA <= '7'
                                GROUP BY LEFT(cg.ID_CUENTA, 2)");
        echo $this->dbutil->csv_from_result($query);
//        if($query->num_rows() > 0 )
//        {
//            return $query->result();
//        }
    }

    public function insert_entry()
    {
        $this->title    = $_POST['title']; // please read the below note
        $this->content  = $_POST['content'];
        $this->date     = time();

        $this->db->insert('entries', $this);
    }

    public function update_entry()
    {
        $this->title    = $_POST['title'];
        $this->content  = $_POST['content'];
        $this->date     = time();

        $this->db->update('entries', $this, array('id' => $_POST['id']));
    }


}

