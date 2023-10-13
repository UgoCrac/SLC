import Cookies from 'js-cookie';
import { useState } from "react";
import { useRouter } from 'next/router';

export default function login() {
    const router = useRouter();
    // Définit router pour ensuite rediriger vers l'accueil

    const [formData, setFormData] = useState({
        username: "",
        password: ""
    });  // State pour stocker les infos du formulaire


    const [error, setError] = useState(null); // State pour stocker les erreurs


    const handleChange = (e) => {
        // Actualiser formData avec les infos du formulaire
        const { name, value } = e.target;
        setFormData({
            ...formData, // Copie toute les paires dans un nouvel objet
            [name]: value, // Ajoute une nouvelle paire clé valeur a formData (name et value de mes inputs)
        });
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        try {
            // Requête POST pour créer un compte utilisateur avec les données du formulaire
            const response = await fetch("https://127.0.0.1:8000/api/login_check", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json", // Spécifie les headers de la requete HTTP
                },
                body: JSON.stringify(formData), // Spécifie le body (ici mes paires clé valeurs dans formData)
            });

            if (response.ok) {
                const data = await response.json(); // Récupére la réponse a ma requete (ici le token JWT)
                const token = data.token; // Stocker le token
                Cookies.set('auth_token', token); // Conserver le token dans un cookie 
                router.push('/'); // Rediriger vers l'accueil

            } else {
                // Si la réponse n'est pas OK
                const data = await response.json();
                setError("Erreur " + data.code + ":" + data.message); // Modifie le state error en ajoutant le code et le message de l'erreur
            }
        } catch (error) {
            setError("Erreur de communication avec le serveur : " + error.message);
        }
    };



    return (
        <>
            <h1>Se connecter :</h1>
            <form onSubmit={handleSubmit}>
                <div>
                    <label>Adresse mail :</label>
                    <input type="text" name="username" value={formData.username} onChange={handleChange} />
                </div >
                <div>
                    <label>Mot de passe :</label>
                    <input type="password" name="password" value={formData.password} onChange={handleChange} />
                </div >
                <button type="submit">Se connecter</button>
                <div>
                    {error && <p style={{ color: "red" }}>{error}</p>}
                </div>
            </form>
        </>
    );
}