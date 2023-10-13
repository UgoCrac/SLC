import { useState } from "react";
import { useRouter } from 'next/router';

export default function CreateClients() {
    const router = useRouter();

    const [formData, setFormData] = useState({
        nom: "",
        prenom: "",
        email: "",
        password: ""
    }); // State pour stocker les données du formulaire

    const [error, setError] = useState(null);

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
            const response = await fetch("https://127.0.0.1:8000/api/user", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json", // Spécifie les headers de la requete HTTP
                },
                body: JSON.stringify(formData), // Spécifie le body (ici mes paires clé valeurs dans formData)
            });

            if (response.ok) {
                router.push('/clients/login'); // Rediriger vers le login
            } else {
                // Si la réponse n'est pas OK
                const data = await response.json();
                console.log(data);
                setError(data); // Mettre a jour state error avec mon message défini par mon API
            }
        } catch (error) {
            setError("Erreur de communication avec le serveur : " + error.message);
        }
    };

    return (
        <div>
            <h1>Création de compte utilisateur</h1>
            <form onSubmit={handleSubmit}>
                <div>
                    <label>Nom</label>
                    <input
                        type="text"
                        name="nom"
                        value={formData.nom}
                        onChange={handleChange}
                    />
                </div>
                <div>
                    <label>Prénom</label>
                    <input
                        type="text"
                        name="prenom"
                        value={formData.prenom}
                        onChange={handleChange}
                    />
                </div>
                <div>
                    <label>Email</label>
                    <input
                        type="email"
                        name="email"
                        value={formData.email}
                        onChange={handleChange}
                    />
                </div>
                <div>
                    <label>Mot de passe</label>
                    <input
                        type="password"
                        name="password"
                        value={formData.password}
                        onChange={handleChange}
                    />
                </div>
                {error && <p style={{ color: "red" }}>{error}</p>}
                <button type="submit">Créer le compte</button>
            </form>
        </div>
    );
}