<?
// contrib : Fabrizio Paolucci
// GNU general  public license
class html_table {
  var $cols = 0;
  var $rows = 0;
  var $cell = array();
  var $html = '';
  var $cell_content = '&nbsp;'; //default cell content.
  var $content_style = ''; //default content style. content is surrounded by <SPAN> tag.

  var $col_style = array(); // default style for table column. enclosed in <TD> tags.
  var $col_cell_style = array(); // default style for column cells. enclosed in <span> tags.

  var $cell_style = ''; //  default cell style. enclosed in <TD> tags.
  var $table_style = ''; // default table style. enclosed in <TABLE> tags.
  var $table_caption;

function cell_init() {
  return array(
    'content'   => $this->cell_content,
    'style'     => '',
    'cell_style'=> '');
}

function Set_caption($titolo)
{
$this->table_caption=$titolo ;
}

function init($parameters) {
  if (isset($parameters['cols'])) $this->cols = $parameters['cols'];
  if (isset($parameters['rows']))$this->rows = $parameters['rows'];

  if (isset($parameters['content']))$this->cell_content = $parameters['content'];


  for ($row = 1; $row <= $this->rows; $row++) {
    for ($col = 1; $col <= $this->cols; $col++) {
      $this->cell[$row][$col] = $this->cell_init();
    }
  }
}

 // init table with total number of element =  number of array element
 // and fill before  colonm by fixing the row numbers
 function init_fill_bf_col(&$contentcell, $row_n)
 {
   $totalc=0 ;
  $ntot=count( $contentcell);   // total number  of array element
  $col_n=ceil($ntot/$row_n);   // :ceil is the  integer  excedeed approx
  $this->init(array("cols" => $col_n , "rows" => $row_n ));
    for ($col = 1; $col <= $this->cols; $col++)
                                                            {
    // fill first col
    for ($row = 1; $row <= $this->rows; $row++) {
               if ($totalc <= $ntot ) $this->cell[$row][$col]["content"] = $contentcell[$totalc];
               $totalc++  ;
                                                                   }
                                                              }

 }


 function init_fill_bf_row(&$contentcell,$col_n)
  {
    $totalcount=0 ;
  $ntot=count( $contentcell);
  $row_n=ceil($ntot/$col_n);
  $this->init(array("cols" => $col_n , "rows" => $row_n ));


    for ($row = 1; $row <= $this->rows; $row++) {
    // fill first row

    for ($col = 1; $col <= $this->cols; $col++) {
      if ($totalcount <= $ntot ) $this->cell[$row][$col]["content"] = $contentcell[$totalcount];
           $totalcount++ ;
                                                               }
                                                                   }


  }

function add_rows($num_rows) {
  for ($row = 1; $row <= $num_rows; $row++) {
    for ($col = 1; $col <= $this->cols; $col++) {
      $this->cell[$row + $this->rows][$col] = $this->cell_init();
    }
  }
  $this->rows += $num_rows;
}

function add_cols($num_cols) {
  for ($row = 1; $row <= $this->rows; $row++) {
    for ($col = 1; $col <= $num_cols; $col++) {
      $this->cell[$row][$col+$this->cols] = $this->cell_init();
    }
  }
  $this->cols += $num_cols;
}

function code() {
  if (!empty($this->html)) return 1;
  $this->html = '<TABLE '.$this->table_style.'>'."\n";
  if (!empty($this->table_caption)){
                                         $this->html .="<caption>".$this->table_caption."</caption> \n" ;
                                           }
  for ($row = 1; $row <= $this->rows; $row++) {
    $this->html .= '  <TR>'."\n";
    for ($col = 1; $col <= $this->cols; $col++) {
      $extra = '';
      //check if "colspan" defined. if so then hide cells that get merged.
      if (isset($this->cell[$row][$col]['colspan'])) {
        $extra .= 'COLSPAN="'.$this->cell[$row][$col]['colspan'].'"';
        for ($hidden_col = 1; $hidden_col < $this->cell[$row][$col]['colspan']; $hidden_col++) {
          $this->cell[$row][$col+$hidden_col]["hide"] = true;
          //check if "rowspan" defined. if so then propogate "colspan" into merged rows.
          if (isset($this->cell[$row][$col]["rowspan"])) {
            for ($hidden_row = 1; $hidden_row < $this->cell[$row][$col]['rowspan']; $hidden_row++) {
              $this->cell[$row+$hidden_row][$col]["colspan"] = $this->cell[$row][$col]['colspan'];
            }
          }
        }
      }
      //check if "rowspan" defined. if so then hide cells that get merged.
      if (isset($this->cell[$row][$col]["rowspan"])) {
        $extra .= 'ROWSPAN="'.$this->cell[$row][$col]['rowspan'].'"';
        for ($hidden_row = 1; $hidden_row < $this->cell[$row][$col]['rowspan']; $hidden_row++) {
          $this->cell[$row+$hidden_row][$col]["hide"] = true;
        }
      }
      // code to draw cell html...
      if (isset($this->cell[$row][$col]['hide'])) continue; // if hide then skip this cell.

      // otherwise draw cell with style...
      if (!empty($this->cell[$row][$col]['cell_style']))
        $this->html .= '    <TD '.$this->cell[$row][$col]['cell_style'].' '.$extra.'>';
      else if (!empty($this->col_style[$col]))
        $this->html .= '    <TD '.$this->col_style[$col].' '.$extra.'>';
      else
        $this->html .= '    <TD '.$this->cell_style.' '.$extra.'>';

      // draw content of cell with style...
      if (!empty($this->cell[$row][$col]['style'])) $this->html .= '<SPAN '.$this->cell[$row][$col]['style'].'>';
      else if (!empty($this->col_cell_style[$col])) $this->html .= '<SPAN '.$this->col_cell_style[$col].'>';
      else if (!empty($this->content_style)) $this->html .= '<SPAN '.$this->content_style.'>';

      $this->html .= $this->cell[$row][$col]['content'];

      if (!empty($this->cell[$row][$col]['style']) or !empty($this->col_cell_style[$col]) or !empty($this->content_style))
        $this->html .= '</SPAN>';
    # missing / for closing tags
      $this->html .= '</TD>'."\n";
     # idems
    }
    $this->html .= '  </TR>'."\n";
  }
  $this->html .= '</TABLE>'."\n";
}

function display() {
  if (empty($this->html)) $this->code();
  print $this->html;
}

} // end of class html_table.


class html_table_pretty extends html_table {
  var $background = '';
  var $border_width = '';
  var $border_color = '';
  var $html_pretty = '';

function code_pretty() {
  $this->code();
  $border_style = '';

  //if (!empty($this->border_width)) $border_style .= ' CELLSPACING="'.$this->border_width.'"';
  //if (!empty($this->border_color)) $border_style .= ' BGCOLOR="'.$this->border_color.'"';
  //if (!empty($this->border_color)) $border_style .= ' BORDERCOLOR="'.$this->border_color.'"';

  if (!empty($this->border_width)) $border_style .= ' CELLSPACING="0" CELLPADDING="0" BORDER="'.$this->border_width.'"';
  if (!empty($this->border_color)) $border_style .= ' BORDERCOLOR="'.$this->border_color.'"';


  $this->html_pretty  = '<TABLE'.$border_style.'><TR><TD '.$this->background.'>'."\n";
  $this->html_pretty .= $this->html;
  $this->html_pretty .='<TD><TR><TABLE>'."\n";
}

function display_pretty() {
  if (empty($this->html_pretty)) $this->code_pretty();
  print $this->html_pretty;
}

function gethtml($recodehtml )
{

if($recodehtml) {
       $this->code_pretty();
       return  $this->html_pretty;
                   } else
                   {
                   if (empty($this->html_pretty)) $this->code_pretty();
                   return  $this->html_pretty;
                   }
 }



} // end of class html_table_pretty
?>
