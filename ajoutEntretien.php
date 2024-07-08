<form id="addMaintenanceForm" action="ajoutEntretien.php" method="POST">
                        <label for="vehicule">Véhicule:</label>
                        <select name="vehicule" style="width: 40%; height: 45px;" required>
                            <!-- Options chargées depuis la base de données -->
                            <?php
                            include 'config.php';
                            $sql = "SELECT label FROM llx_product";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo "<option value='" . $row['label'] . "'>" . $row['label'] . "</option>";
                                }
                            } else {
                                echo "<option disabled>Aucun véhicule disponible</option>";
                            }
                            $conn->close();
                            ?>
                        </select>

                        <label for="nom_conducteur">Nom du conducteur:</label>
                        <select name="nom_conducteur" style="width: 80%; height: 35px;" required>
                            <!-- Options chargées depuis la base de données -->
                            <?php
                            include 'config.php';
                            $sql = "SELECT lastname FROM llx_user WHERE entity=2";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo "<option value='" . $row['lastname'] . "'>" . $row['lastname'] . "</option>";
                                }
                            } else {
                                echo "<option disabled>Aucun conducteur trouvé</option>";
                            }
                            $conn->close();
                            ?>
                        </select>

                        <label for="tache_entretien">Tâche d'entretien:</label>
                        <input type="text" name="tache_entretien" placeholder="Tâche d'entretien" required>

                        <label for="etat_tache">État de la tâche:</label>
                        <input type="text" name="etat_tache" placeholder="État de la tâche" required>

                        <label for="date_entretien">Date de l'entretien:</label>
                        <input type="date" name="date_entretien" placeholder="Date de l'entretien" required>

                        <label for="nom_technicien">Nom du technicien:</label>
                        <input type="text" name="nom_technicien" placeholder="Nom du technicien" required>

                        <input type="submit" style="width: 190px; height: 40px; background-color: #007bff; color: white; border: none; border-radius: 6px;" value="Ajouter Entretien">
                    </form>