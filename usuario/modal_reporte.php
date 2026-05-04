        
                                        <div class="modal fade" id="modal_reporte" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
                                            <div class="modal-dialog modal-xl" role="document">
                                                <div class="modal-content">
    
                                                    <div class="uk-child-width-1-2@s" uk-grid style="display:contents">
                                                        <div style="width: 100%;">
                                                            <h4 class="uk-text-center uk-text-uppercase uk-background-default uk-padding-small">Nombre de columnas</h4>
                                                            <div id="areaAgenda" style="border-radius:10px;background-color:#6077731f" class="uk-padding-small uk-background-secondary" uk-sortable="group: sortable-group">
    
                                                                <?php
                                                                $sql=" select COLUMN_NAME from INFORMATION_SCHEMA.COLUMNS where TABLE_SCHEMA = 'dbo' and TABLE_NAME = 'tblUsuario' order by ORDINAL_POSITION  ";
                                                                $query=sqlsrv_query($con,$sql);
                                                                while($row=sqlsrv_fetch_array($query)){
                                                                        $nombre_columna=$row['COLUMN_NAME'];
    
                                                                        echo '<div class="uk-margin display-caja">
                                                                                    <div style="padding:8px" class="uk-card uk-card-hover uk-card-default uk-card-body uk-card-small sub-div">
                                                                                        <span class="uk-flex uk-flex-left uk-flex-middle"><a href="#" style="text-decoration: none;padding:20px;border-radius:7px" class="bordes uk-button-primary" uk-icon="forward">'.$nombre_columna.'</a></span>
                                                                                        <span class="uk-flex uk-flex-right send-list"><input type="hidden" value="'.$nombre_columna.'">
                                                                                            <span>
                                                                                    </div>
                                                                                </div>';
                                                                }
                                                                ?>                                                    
    
                                                            </div>
                                                        </div>
                                                        <!-- SE ALMACENAN AQUÍ -->
                                                        <div style="width:100%">
                                                            <div class="uk-text-center uk-text-uppercase uk-background-default uk-padding-small uk-margin-remove-bottom h4">LISTA
                                                                
                                                                <a class="uk-button uk-button-primary uk-float-right" onclick="reporte_pdf('areaImprimir')">
                                <span uk-icon="print"></span>REPORTE PDF
                              </a>
                                                                <a id="btnBorrar" href="#" class="uk-button uk-button-danger uk-float-right" >Limpiar</a>
                                                            </div>
                                                            <div style="padding:10px;background-color:#1e87f03d" id="areaImprimir" class="uk-card uk-card-primary uk-card-body uk-maring-right" uk-sortable="group: sortable-group">
                                                            </div>
                                                        </div>
                                                    </div>
    
    
                                                    <div class="modal-footer">                                    
                                                        <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">Cerrar</button>
                                                    </div>
    
                                                </div>
                                            </div>
                                        </div>
    
                                