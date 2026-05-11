<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title text-center mb-4">Inscription - Étape 2 : Informations de Santé</h2>
                
                <form action="<?= base_url('inscription/etape2') ?>" method="POST">
                    <?= csrf_field() ?>
                    
                    <div class="alert alert-info">
                        <strong>Info :</strong> Ces informations nous permettront de calculer votre IMC et de vous proposer des régimes adaptés.
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="taille" class="form-label">Taille (en cm) *</label>
                            <input type="number" class="form-control" id="taille" name="taille" 
                                   step="0.1" value="<?= old('taille') ?>" required>
                            <small class="form-text text-muted">Ex: 170</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="poids" class="form-label">Poids (en kg) *</label>
                            <input type="number" class="form-control" id="poids" name="poids" 
                                   step="0.1" value="<?= old('poids') ?>" required>
                            <small class="form-text text-muted">Ex: 70</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <p class="text-center">
                            <strong>Votre IMC sera calculé automatiquement</strong>
                        </p>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Terminer l'inscription</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
