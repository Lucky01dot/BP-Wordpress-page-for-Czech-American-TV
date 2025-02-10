<?php

	/**
	 * Class GT_Admin_Output_Manager manages methods for printing admin-oriented HTML output
	 */
	class GT_Admin_Output_Manager {
		/**
		 * Default constructor.
		 */
		public function __construct() {
		}

		#region PUBLIC METHODS

		/**
		 * Generates Html output for main information about data.
		 * @return void
		 */
		public function main_info() {
			$output_text = "<div class='wrap'>
	        <h1>Database Status</h1>
	        <hr>";

				$output_text .= "
				<div id='gt-table-info'>
					<table class='table'>
					 <thead class='thead-dark'>
					    <tr>
					      <th scope='col'>Table name</th>
					      <th scope='col'>Total number of records</th>
					    </tr>
					  </thead>
					  <tbody>
						  
					  </tbody>
					</table>
				</div>
			</div>
			";

			echo( $output_text );
		}

		/**
		 * Generates Html output for first names imports
		 * @return void
		 */
		public function basic_settings() {
			$output_text = "<div class='wrap'>
            <h1>Manage Basic Data Sources</h1>
            This settings page allows you to manage some basic data sources of database tables. <br/>
            <hr/>";

			$output_text .= "<h2>First Name Translations</h2>
			</br>
            
            <h4>Insert one translation:</h4>
            
            You can insert one translation. The priority of the translation is an integer number (the lowest number has the highest priority)
            <br/>
           	<br/>
           	<form class='gt-fn_translation-import-one' action='javascript:void(0);' autocomplete='off'>
                <label for='gt-fn_translation-one-name'>Insert one record: </label>
                <input id='gt-fn_translation-one-name' type='text' placeholder='Name cz'> 
                <input id='gt-fn_translation-one-name-en' type='text' placeholder='Name en'>
                <input id='gt-fn_translation-one-priority' type='number' placeholder='Priority'> 
                <input class='gt-submit-button' type='submit' value='Import'>
            </form> 
            </br>
            
            <h4>Insert CSV source file:</h4>
            You can insert the whole dataset at once as a CSV file. Old data in the database will be overwritten.
          
            <br /><br />
             
            The CSV source file must have following columns (and their names in the first row):
            <br /><br />
            <ul>
                <li><code>name_en</code> - english name</li>
                <li><code>name_cz</code> - czech name</li>
                <li><code>priority</code> - priority of the translation (integer number), the lowest number has the highest priority</li>
            </ul>
           
            <form class='gt-fn-translations-import' action='javascript:void(0);' autocomplete='off'>
                <label for='gt-fn-translations-import-file'>Import CSV file: </label>
                <input type='file' accept='.csv' id='gt-fn-translations-import-file' name=gt-fn-translations-import-file-name'>
                <input class='gt-submit-button' type='submit' value='Import'>
            </form> 
            
            </br>
            <h4>Export translations as a CSV file:</h4>
            You can export the current translations dataset at once as a CSV file.
            </br>
            <form class='gt-fn-translation-export' action='". esc_url( admin_url('admin-post.php') ) ."' method='POST' autocomplete='off'>
                <label for='gt-submit-button'>Export as CSV: </label>
                <input value='Export' class='gt-submit-button' type='submit' value='Import'>
                <input name='action' value='gt_export_table' type='hidden'>
                <input name='table' value='fn_translation' type='hidden'>
            </form>
           
           	<br/>
           	Here you will see a result message of management operations.
           	<br />
            <b>Status:</b> <span id='gt-fn-translations-import-info'></span>
            </br>
            <div id='gt-fn-translations-import-table'></div>
             <hr>";

			$output_text .= "<h2>First Name Diminutives</h2>
			</br>
            
            <h3>Insert one diminutive:</h3>
            
            You can insert one diminutive.
            </br></br>
            <form class='gt-fn_diminutive-import-one' action='javascript:void(0);' autocomplete='off'>
                <label for='gt-fn_diminutive-import-file'>Insert one record: </label>
                <input id='gt-fn_diminutive-one-name' type='text' placeholder='Name cz'> 
                <input id='gt-fn_diminutive-one-diminutive' type='text' placeholder='Diminutive'> 
                <input class='gt-submit-button' type='submit' value='Import'>
            </form>
            </br>
            
            <h4>Insert CSV source file:</h4>
            You can insert the whole dataset at once as a CSV file. Old data in the database will be overwritten.
          	
          	</br></br>
          	
          	The CSV source file must have following columns (and their names in the first row):
            <br /><br />
            <ul>
                <li><code>name</code> - first name</li>
                <li><code>diminutive</code> - diminutive of the first name</li>
            </ul>
           
            <form class='gt-fn-diminutives-import' action='javascript:void(0);' autocomplete='off'>
                <label for='gt-fn-diminutives-import-file'>Import CSV file: </label>
                <input type='file' accept='.csv' id='gt-fn-diminutives-import-file' name='gt-fn-diminutives-import-file-name'>
                <input class='gt-submit-button' type='submit' value='Import'>
            </form> 
            
            </br>
            <h4>Export diminutives as a CSV file:</h4>
            You can export the current diminutives dataset at once as a CSV file.
            </br>
            <form class='gt-fn-diminutive-export' action='". esc_url( admin_url('admin-post.php') ) ."' method='POST' autocomplete='off'>
                <label for='gt-submit-button'>Export as CSV: </label>
                <input value='Export' class='gt-submit-button' type='submit' value='Import'>
                <input name='action' value='gt_export_table' type='hidden'>
                <input name='table' value='fn_diminutive' type='hidden'>
            </form>
           
            <br/>
           	Here you will see a result message of management operations.
           	<br />
            <b>Status:</b> <span id='gt-fn-diminutives-import-info'></span>
            </br>
            <div id='gt-fn-diminutives-import-table'></div>
             <hr>";

			$output_text .= "<h2>Last Name Explanations</h2>

			<h4>Insert one explanation:</h4>
			
			You can insert one translation. The priority of the translation is an integer number (the lowest number has the highest priority)
            <br/>
           	<br/>
           	<form class='gt-ln-explanation-import-one' action='javascript:void(0);' autocomplete='off'>
                <label for='gt-ln-explanation-import-file'>Insert one record: </label>
                <input id='gt-ln-explanation-one-name' type='text' placeholder='Name cz'> 
                <input id='gt-ln-explanation-one-explanation' type='text' placeholder='Explanation'> 
                <input class='gt-submit-button' type='submit' value='Import'>
            </form> 
           	</br>
            
            <h4>Insert CSV source file:</h4>
            You can insert the whole dataset at once as a CSV file. Old data in the database will be overwritten.
          
            <br /><br />
           	
           	The CSV source file must have following columns (and their names in the first row):
            <br /><br />
            <ul>
                <li><code>name</code> - czech last name</li>
                <li><code>explanation</code> - last name meaning explanation</li>
            </ul>
            
            <form class='gt-ln-explanation-import' action='javascript:void(0);' autocomplete='off'>
                <label for='gt-ln-explanation-import-file'>Import CSV file: </label>
                <input type='file' accept='.csv' id='gt-ln-explanation-import-file' name='gt-ln-explanation-import-file-name'>
                <input class='gt-submit-button' type='submit' value='Import'>
            </form> 
            </br>
            <h4>Export explanations as a CSV file:</h4>
            You can export the current explanations dataset at once as a CSV file.
            </br>
            
            </br>
            <form class='gt-ln-explanation-export' action='". esc_url( admin_url('admin-post.php') ) ."' method='POST' autocomplete='off'>
                <label for='gt-submit-button'>Export as CSV: </label>
                <input value='Export' class='gt-submit-button' type='submit' value='Import'>
                <input name='action' value='gt_export_table' type='hidden'>
                <input name='table' value='ln_explanation' type='hidden'>
            </form> 
            
            
            <br/>
           	Here you will see a result message of management operations.
           	<br />
            <b>Status:</b> <span id='gt-ln-explanation-import-info'></span>
            </br>
            <div id='gt-ln-explanation-import-table'></div>
            <hr>";

            $output_text .= "<h2>CZ to EN translations</h2>
            </br>
            
            <h4>Insert one translation:</h4>
            
            You can insert one translation. 
            <br/>
           	<br/>
           	<form class='gt-cz-en-translation-import-one' action='javascript:void(0);' autocomplete='off'>
                <label for='gt-cz-en-translation-one-word'>Insert one record: </label>
                <input id='gt-cz-en-translation-one-word' type='text' placeholder='Word cz'> 
                <input id='gt-cz-en-translation-one-word-en' type='text' placeholder='Word en'>
                <input class='gt-submit-button' type='submit' value='Import'>
                
            </form> 
            </br>
            <h4>Insert CSV source file:</h4>
            You can insert the whole dataset at once as a CSV file. Old data in the database will be overwritten.
          
            <br /><br />
            
            The CSV source file must have following columns:
            <br /><br />
            <ul>
                <li><code>id</code> - word ID</li>
                <li><code>czech_word</code> - czech word</li>
                <li><code>english_translation</code> - english word</li>
                
            </ul>
           
            <form class='gt-cz-en-translation-import'  action='javascript:void(0);' autocomplete='off'>
                <label for='gt-cz-en-translation-import-file'>Import CSV file: </label>
                <input type='file' accept='.csv' id='gt-cz-en-translation-import-file' name='gt-cz-en-translation-import-file-name'>
                <input class='gt-submit-button' type='submit' value='Import'>
                
            </form> 
            
            </br>
            <h4>Export translations as a CSV file:</h4>
            You can export the current translations dataset at once as a CSV file.
            </br>
            <form class='gt-cz-en-translation-export' action='". esc_url( admin_url('admin-post.php') ) ."' method='POST' autocomplete='off'>
                <label for='gt-submit-button'>Export as CSV: </label>
                <input value='Export' class='gt-submit-button' type='submit' value='Import'>
                <input name='action' value='gt_export_table' type='hidden'>
                <input name='table' value='cz_en_translation' type='hidden'>
                
            </form>
           
           	<br/>
           	Here you will see a result message of management operations.
           	<br />
            <b>Status:</b> <span id='gt-cz-en-translation-import-info'></span>
            </br>
            <div id='gt-cz-en-translation-import-table'></div>
            <hr>
            
            <h2>Transcription Rules</h2>
    		You can edit last name transcription rules in the file editor:
            <a href=\"./plugin-editor.php?file=genealogy_tools%2Fincludes%2Fservices%2Frules.txt&plugin=genealogy_tools%2Fcatv_genealogy_tools.php\">Launch Editor</a>
            <hr>";

			$output_text .= "</div>";

			echo( $output_text );
		}

		/**
		 * Generates Html output for last name imports
		 * @return void
		 */
		public function advanced_settings() {
			$output_text = "<div class='wrap'>
            <h1>Manage Advanced Data Relations</h1>
            <hr>";

			$output_text .= "<h2>First Names</h2>
            <b>Table:</b> *" . GT_Tables::FIRST_NAME . " 
            </br></br>
            <ul>
                <li><code>id</code> = id jména</li>
                <li><code>name</code> = jméno</li>
            </ul>
            <form class='gt-fn-import' action='javascript:void(0);' autocomplete='off'>
                <label for='gt-fn-import-file'>Bulk Import (CSV): </label>
                <input type='file' accept='.csv' id='gt-fn-import-file' name='gt-fn-import-file-name'>
                <input class='gt-submit-button' type='submit' value='Import'>
            </form> 
            <b>Status:</b> <span id='gt-fn-import-info'></span>
            </br>
            <div id='gt-fn-import-table'></div>
            
            <hr>";

			$output_text .= "<h2>Last Names</h2>
            <b>Table:</b> *" . GT_Tables::LAST_NAME . "
            </br></br>
            <ul>
		        <li><code>id</code> = id jména</li>
		        <li><code>name</code> = jméno</li>
		        <li><code>count</code> = celková četnost jména</li>
	        </ul>
            <form class='gt-ln-import' action='javascript:void(0);' autocomplete='off'>
            	<input name='security' value='" . wp_create_nonce( "uploadingFile" ) . "' type='hidden'>
	
                <label for='gt-ln-import-file'>Bulk Import (CSV): </label>
                <input type='file' accept='.csv' id='gt-ln-import-file' name='gt-ln-import-file-name'>

                <input class='gt-submit-button' type='submit' value='Import'>
            </form> 
            <b>Status:</b> <span id='gt-ln-import-info'></span>
            </br>
            
            <div id='gt-ln-import-table'></div>
            <hr>";

			$output_text .= "<h2>Last Name Count</h2>
            <b>Table:</b> *" . GT_Tables::LAST_NAME_COUNT . "
            </br></br>
			<ul>
		        <li><code>name_id</code> = id příjmení (FK do tabulky <code>last_name.csv</code>)</li>
		        <li><code>mep_id</code> = id obce s rozšířenou působností (FK do tabulky <code>mep.csv</code>)</li>
		        <li><code>count</code> = četnost</li>
	        </ul>

            <form class='gt-ln-count-import' action='javascript:void(0);' autocomplete='off'>
                <label for='gt-ln-count-import-file'>Bulk Import (CSV): </label>
                <input type='file' accept='.csv' id='gt-ln-count-import-file' name='gt-ln-count-import-file'>
                <input class='gt-submit-button' type='submit' value='Import'>
            </form> 
            <b>Status:</b> <span id='gt-ln-count-import-info'></span>
            </br>
            
            <div id='gt-ln-count-import-table'></div>
            <hr>";

			$output_text .= "<h2>Cities</h2>
            <b>Table:</b> *" . GT_Tables::CITY . "
            </br></br>
            <ul>
                <li><code>id</code> = id obce</li>
                <li><code>name_cz</code> = název obce česky</li>
                <li><code>name_de</code> = název obce německy</li>
                <li><code>district_id</code> = id okresu (FK do tabulky <code>district.csv</code>)</li>
                <li><code>note</code> = poznámka k obci, zatím v češtině, možno v budoucnu přeložit a použít</li>
            </ul>
            <form class='gt-city-import' action='javascript:void(0);' autocomplete='off'>
                <label for='gt-city-import-file'>Bulk Import (CSV): </label>
                <input type='file' accept='.csv' id='gt-city-import-file' name='gt-city-import-file-name'>
                <input class='gt-submit-button' type='submit' value='Import'>
            </form> 
             <b>Status:</b> <span id='gt-city-import-info'></span>
            </br>
            <div id='gt-city-import-table'></div>
            
            <hr>";

			$output_text .= "<h2>Regions</h2>
            <b>Table:</b> *" . GT_Tables::REGION . "
            </br></br>
            <ul>
                <li><code>id</code> = id kraje</li>
                <li><code>name_cz</code> = název kraje česky</li>
                <li><code>name_en</code> = název kraje anglicky</li>
                <li><code>map_code</code> = kód kraje pro Google maps</li>
            </ul>
            <form class='gt-region-import' action='javascript:void(0);' autocomplete='off'>
                <label for='gt-region-import-file'>Bulk Import (CSV): </label>
                <input type='file' accept='.csv' id='gt-region-import-file' name='gt-region-import-file-name'>
                <input class='gt-submit-button' type='submit' value='Import'>
            </form> 
             <b>Status:</b> <span id='gt-region-import-info'></span>
            </br>
            <div id='gt-region-import-table'></div>
            
             <hr>";

			$output_text .= "<h2>Counties</h2>
            <b>Table:</b> *" . GT_Tables::DISTRICT . "
            </br></br>
            <ul>
                <li><code>id</code> = id okresu</li>
                <li><code>name_cz</code> = název okresu česky</li>
                <li><code>name_en</code> = název okresu anglicky</li>
                <li><code>region_id</code> = id kraje (FK do tabulky <code>region.csv</code>)</li>
            </ul>
            <form class='gt-district-import' action='javascript:void(0);' autocomplete='off'>
                <label for='gt-district-import-file'>Bulk Import (CSV): </label>
                <input type='file' accept='.csv' id='gt-district-import-file' name='gt-district-import-file-name'>
                <input class='gt-submit-button' type='submit' value='Import'>
            </form> 
             <b>Status:</b> <span id='gt-district-import-info'></span>
            </br>
            <div id='gt-district-import-table'></div>
            
             <hr>";

			$output_text .= "<h2>Municipalities With Extended Powers</h2>
            <b>Table:</b> *" . GT_Tables::MEP . "
            </br></br>
            <ul>
                <li><code>id</code> = id okresu</li>
                <li><code>name_cz</code> = název obce česky</li>
                <li><code>name_de</code> = název obce německy</li>
                <li><code>region_id</code> = id kraje (FK do tabulky <code>region.csv</code>)</li>
                <li><code>lat</code> = zeměpisná šířka</li>
                <li><code>lng</code> = zeměpisná výška</li>
            </ul>
            <form class='gt-mep-import' action='javascript:void(0);' autocomplete='off'>
                <label for='gt-mep-import-file'>Bulk Import (CSV): </label>
                <input type='file' accept='.csv' id='gt-mep-import-file' name='gt-mep-import-file-name'>
                <input class='gt-submit-button' type='submit' value='Import'>
            </form> 
             <b>Status:</b> <span id='gt-mep-import-info'></span>
            </br>
            <div id='gt-mep-import-table'></div>
            
             <hr>";

			$output_text .= "</div>";

            $output_text .= "<h2>Translation from Latin to Czech</h2>
            <b>Table:</b> *" . GT_Tables::LA_CZ_TRANSLATION . "
            </br></br>
            <ul>
                <li><code>id</code> = id překladu</li>
                <li><code>latin_word</code> = latinský slovo</li>
                <li><code>czech_translation</code> = český překlad</li>
                
            </ul>
            <form class='gt-la-cz-translation-import' action='javascript:void(0);' autocomplete='off'>
                <label for='gt-la-cz-translation-import-file'>Bulk Import (CSV): </label>
                <input type='file' accept='.csv' id='gt-la-cz-translation-import-file' name='gt-la-cz-translation-import-file-name'>
                <input class='gt-submit-button' type='submit' value='Import'>
            </form> 
             <b>Status:</b> <span id='gt-la-cz-translation-import-info'></span>
            </br>
            <div id='gt-la-cz-translation-import-table'></div>
            
             <hr>";

            $output_text .= "</div>";

			echo( $output_text );
		}

		#endregion
	}