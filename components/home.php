<?php
//i arxiki selida me thn anazitisi kai thn emfanisi apotelesmatwn ths anazitisis
if(!isset($_SESSION)){ 
    session_start(); 
}
$_SESSION["currentPage"]='home';


?>
<!-- ANAZITISH   -->
<main >
    <div class="search">
      <form id='searchForm' class="margin-sides-1pr">
    

        <div class="row">
            <div class="col">
                <div class="row offset-xl-2 offset-md-1 mb-2 mt-3">

                    <div class="offset-sm-1 offset-md-0 col-lg-1 col-md-1 col-sm-2">Πόλη:</div>
                    <div class="col-lg-3 col-md-3 col-sm-8 "> 
                        <div class="autoComplete">
                            <label for="city" class="sr-only">Πόλη</label>
                            <input type="text"  id="city" class="mb-2 form-control autoInput" placeholder="Πόλη"  autocomplete="off" required autofocus>
                            <div class="lista" id='cityLista'> </div>
                        </div>
                     
                     </div>
                     
                    <div class="offset-sm-1 offset-md-0 col-lg-1 col-md-2 col-sm-2">Ζώο:</div>
                    <div class="col-lg-2 col-md-2 col-sm-8 mb-2"> <select id="type" class="custom-select d-block w-100 my-select" >
                                                     <option value="Σκύλος">Σκύλος</option>
                                                     <option value="Γάτα">Γάτα</option>
                                                    </select>
                     </div>

                   <div class=" offset-sm-1 offset-md-0 col-lg-1 col-md-2 col-sm-2">Μέγεθος:</div>
                   <div class="col-lg-2 col-md-2 col-sm-8 "> <select id="size" class="custom-select d-block w-100" >
                                                    <option value="all">Όλα</option>
                                                    <option value="Μικρό">Μικρό</option>
                                                    <option value="Μεσαίο">Μεσαίο</option>
                                                    <option value="Μεγάλο">Μεγάλο</option>
                                                   </select>
                     </div>
                  </div>
              </div>
            </div>


            <div class="row">
                <div class="col">
                   <div class="row offset-xl-2 offset-md-1  mb-3">

                   <div class="offset-sm-1 offset-md-0 col-lg-1 col-md-1 col-sm-2">Ράτσα:</div>
                   <div class="col-lg-3 col-md-3 col-sm-8 "> 
                      <div class="autoComplete">
                          <label for="race" class="sr-only">Ράτσα</label>
                          <input type="text"  id="race" class="mb-2 form-control autoInput" placeholder="Ράτσα"  autocomplete="off"  >
                          <div class="lista" id='raceLista'> </div>
                      </div>

                    </div>

                    <div class="offset-sm-1 offset-md-0 col-lg-1 col-md-2 col-sm-2">Ηλικία:</div>
                    <div class="col-lg-2 col-md-2 col-sm-8 mb-2"> <select   id="age" class="custom-select d-block w-100" >
                                                     <option value="all">Όλες</option>
                                                     <option value="0-2 μηνών">0-2 μηνών</option>
                                                     <option value="2-6 μηνών">2-6 μηνών</option>
                                                     <option value="6-11 μηνών">6-11 μηνών</option>
                                                     <option value="1 έτους">1 έτους</option>
                                                     <option value="2 έτων">2 έτων</option>
                                                     <option value="3 έτων">3 έτων</option>
                                                     <option value="4 έτων">4 έτων</option>
                                                     <option value="5 έτων">5 έτων</option>
                                                     <option value="6 έτων">6 έτων</option>
                                                     <option value="7 έτων">7 έτων</option> 
                                                     <option value="8 έτων">8 έτων</option>
                                                     <option value="9 έτων">9 έτων</option>
                                                     <option value="10 έτων">10 έτων</option>
                                                     <option value="11 έτων">11 έτων</option>
                                                     <option value="12 έτων">12 έτων</option>
                                                     <option value="13 έτων">13 έτων</option>
                                                     <option value="14 έτων">14 έτων</option>
                                                     <option value="15 έτων">15 έτων</option>
                                                     <option value="16+ έτων">16+ έτων</option>
                                                    </select>
                    </div>

                   <div class="offset-sm-1 offset-md-0 col-lg-1 col-md-2 col-sm-2">Φύλο</div>
                   <div class="col-lg-2 col-md-2 col-sm-8 "> <select id="gender" class="custom-select d-block w-100" >
                                                    <option value="all">Όλα</option>
                                                    <option value="Αρσενικό">Αρσενικό</option>
                                                    <option value="Θηλυκό">Θηλυκό</option>
                                                   </select>
                    </div>

                </div>
            </div>
          </div>

          <div class="row">
            <div class="col">
               <div class="row offset-xl-2 offset-md-1  mb-3">

                  <div class="offset-sm-1 offset-md-0 col-xl-2 col-lg-2 col-md-2 col-sm-3 mb-3"> <div class="custom-control custom-checkbox">
                       <input type="checkbox" class="custom-control-input" id="adopt">
                       <label class="custom-control-label" for="adopt">Υιοθεσία</label>
                       </div> 
                  </div>

                  <div class="col-xl-2 col-lg-2 col-md-2 col-sm-3 mb-3"> 
                      <div class="custom-control custom-checkbox">
                      <input type="checkbox" class="custom-control-input" id="found">
                      <label class="custom-control-label" for="found">Βρέθηκε</label>
                      </div> 
                  </div>

                  <div class="col-xl-1 col-lg-2 col-md-2 col-sm-2 mb-3"> 
                      <div class="custom-control custom-checkbox">
                      <input type="checkbox" class="custom-control-input" id="searched">
                      <label class="custom-control-label" for="searched">Αναζητείται</label>
                      </div> 
                  </div>

                  <div class=" offset-xl-3 offset-lg-1 offset-md-2 offset-lg-0 offset-sm-1 col-xl-2 col-lg-3 col-md-4 col-sm-10 mt-3"> 
                      <button id="buttonSearch" class="btn btn-lg btn-dark btn-block " type="submit">Αναζήτηση</button>
                  </div>

                </div>
              </div>
            </div>
      </form>

    </div>

  <!-- div gia emfanisi alerts  -->
<div id="alert-placeholderHome"></div>

 <!-- Emfanisi apotelesmatwn  -->
  <div class="album py-5">
    <div class="container" id="result-container">
      <div class="row" id="result-page"></div>
    </div>
  </div>

  
  <div class="mt-4">
  <!-- nav gia emfanisi pagitation ths emfanisis apotelesmatwn  -->
  <nav aria-label="Search results pagination ">
    <ul id="cardsPaginationUl" class="pagination row no-gutters justify-content-center">
    </ul>
  </nav>
</div>





</main>