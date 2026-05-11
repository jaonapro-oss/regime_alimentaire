<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title text-center mb-4">Inscription - Étape 1 : Informations Personnelles</h2>
                
                <form action="<?= base_url('inscription/etape1') ?>" method="POST">
                    <?= csrf_field() ?>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nom" class="form-label">Nom *</label>
                            <input type="text" class="form-control" id="nom" name="nom" 
                                   value="<?= old('nom') ?>" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="prenom" class="form-label">Prénom *</label>
                            <input type="text" class="form-control" id="prenom" name="prenom" 
                                   value="<?= old('prenom') ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?= old('email') ?>" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="mot_de_passe" class="form-label">Mot de passe *</label>
                            <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" required>
                            <small class="form-text text-muted">Minimum 6 caractères</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="mot_de_passe_confirm" class="form-label">Confirmer mot de passe *</label>
                            <input type="password" class="form-control" id="mot_de_passe_confirm" name="mot_de_passe_confirm" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="genre" class="form-label">Genre *</label>
                            <select class="form-control" id="genre" name="genre" required>
                                <option value="">-- Sélectionner --</option>
                                <option value="homme" <?= old('genre') === 'homme' ? 'selected' : '' ?>>Homme</option>
                                <option value="femme" <?= old('genre') === 'femme' ? 'selected' : '' ?>>Femme</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="date_naissance" class="form-label">Date de naissance *</label>
                            <input type="date" class="form-control" id="date_naissance" name="date_naissance" 
                                   value="<?= old('date_naissance') ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="telephone" class="form-label">Téléphone</label>
                        <input type="tel" class="form-control" id="telephone" name="telephone" 
                               value="<?= old('telephone') ?>">
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Continuer vers l'étape 2</button>
                </form>

                <hr>
                <p class="text-center">
                    Vous avez un compte ? 
                    <a href="<?= base_url('login') ?>">Se connecter</a>
                </p>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
