<?php


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
    header('Location: index.php?page=connexion');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du formulaire
    $data = [
        'nom' => trim($_POST['nom'] ?? ''),
        'prenom' => trim($_POST['prenom'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'pseudo' => trim($_POST['pseudo'] ?? ''),
        'telephone' => trim($_POST['telephone'] ?? ''),
        'adresse' => trim($_POST['adresse'] ?? ''),
        'date_de_naissance' => $_POST['date_de_naissance'] ?? '',
        'nouveau_password' => $_POST['nouveau_password'] ?? '',
        'confirm_password' => $_POST['confirm_password'] ?? ''
    ];
    
    // Validation des données
    $errors = [];
    
    if (empty($data['nom'])) $errors[] = "Le nom est requis";
    if (empty($data['prenom'])) $errors[] = "Le prénom est requis";
    if (empty($data['email'])) $errors[] = "L'email est requis";
    if (empty($data['pseudo'])) $errors[] = "Le pseudo est requis";
    
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format d'email invalide";
    }
    
    // Validation du mot de passe (si fourni)
    if (!empty($data['nouveau_password'])) {
        if (strlen($data['nouveau_password']) < 6) {
            $errors[] = "Le nouveau mot de passe doit contenir au moins 6 caractères";
        }
        if ($data['nouveau_password'] !== $data['confirm_password']) {
            $errors[] = "Les mots de passe ne correspondent pas";
        }
    }
    
    try {
        require_once ROOT_PATH . '/Classes/Auth.php';
        $auth = new Auth();
        
        // Vérifier que l'email n'est pas déjà utilisé par un autre utilisateur
        if ($auth->emailExistsForOtherUser($data['email'], $_SESSION['user_id'])) {
            $errors[] = "Cet email est déjà utilisé par un autre utilisateur";
        }
        
        // Vérifier que le pseudo n'est pas déjà utilisé par un autre utilisateur
        if ($auth->pseudoExistsForOtherUser($data['pseudo'], $_SESSION['user_id'])) {
            $errors[] = "Ce pseudo est déjà utilisé par un autre utilisateur";
        }
        
        // Gestion de l'upload de photo
        if (isset($_FILES['photo_profil']) && $_FILES['photo_profil']['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $maxSize = 2 * 1024 * 1024; // 2Mo
            
            if (!in_array($_FILES['photo_profil']['type'], $allowedTypes)) {
                $errors[] = "Format de fichier non autorisé. Utilisez JPG, PNG ou GIF";
            }
            
            if ($_FILES['photo_profil']['size'] > $maxSize) {
                $errors[] = "La taille du fichier ne doit pas dépasser 2Mo";
            }
            
            if (empty($errors)) {
                $data['photo_de_profil'] = file_get_contents($_FILES['photo_profil']['tmp_name']);
            }
        }
        
        // Si il y a des erreurs de validation
        if (!empty($errors)) {
            $_SESSION['error_message'] = implode('<br>', $errors);
            header('Location: index.php?page=profil');
            exit;
        }
        
        // Mettre à jour le profil
        $result = $auth->updateProfile($_SESSION['user_id'], $data);
        
        if ($result) {
            // Mettre à jour les données de session si nécessaire
            $_SESSION['user_name'] = $data['prenom'] . ' ' . $data['nom'];
            $_SESSION['user_email'] = $data['email'];
            
            $_SESSION['success_message'] = "✅ Votre profil a été mis à jour avec succès !";
            header('Location: index.php?page=profil');
            exit;
        } else {
            $_SESSION['error_message'] = "❌ Erreur lors de la mise à jour du profil. Veuillez réessayer.";
            header('Location: index.php?page=profil');
            exit;
        }
        
    } catch (Exception $e) {
        error_log("Erreur lors de la mise à jour du profil : " . $e->getMessage());
        $_SESSION['error_message'] = "❌ Une erreur est survenue lors de la mise à jour.";
        header('Location: index.php?page=profil');
        exit;
    }
    
} else {
    // Si la requête n'est pas en POST, rediriger vers le profil
    header('Location: index.php?page=profil');
    exit;
}
?>