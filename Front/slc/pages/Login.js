import React, { useState } from 'react';
import Cookies from 'js-cookie';
import { useRouter } from 'next/router';

function Login() {
    const router = useRouter();

    const [formData, setFormData] = useState({
        username: "",
        password: ""
    })

    const [error, setError] = useState(null); // State pour stocker les erreurs

    const handleEmailChange = (e) => {
        const { name, value } = e.target;
        setFormData({
            ...formData,
            [name]: value,
        });
    };

    const handlePasswordChange = (e) => {
        const { name, value } = e.target;
        setFormData({
            ...formData,
            [name]: value,
        });
    };

    const handleLogin = async (e) => {
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
                console.log(token);
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

    const handleForgotPassword = () => {
        // Gérez ici l'action "Mot de passe oublié"
        // Par exemple, afficher un message ou rediriger vers une page de réinitialisation de mot de passe.
    };

    return (
        <div>
            <h1 className='text-center pt-3'>ZONE ADMINISTRATION</h1>
            <form>
                <div className="form-group d-flex justify-content-center mt-5">
                    <label htmlFor="email" className='mt-2'>Identifiant :</label>
                    <input
                        type="email"
                        className="form-control w-25 ms-5"
                        id="email"
                        name='username'
                        placeholder="Entrez votre identifiant"
                        value={formData.username}
                        onChange={handleEmailChange}
                    />
                </div>
                <div className="form-group d-flex justify-content-center mt-3">
                    <label htmlFor="password" className='mt-2'>Mot de passe :</label>
                    <input
                        type="password"
                        className="form-control w-25 ms-4"
                        id="password"
                        name='password'
                        placeholder="Entrez votre mot de passe"
                        value={formData.password}
                        onChange={handlePasswordChange}
                    />
                </div>
                <div className='d-flex justify-content-center mt-4'>
                    <button type="button" className="btn btn-primary" onClick={handleLogin}>
                        Se connecter
                    </button>
                </div>
            </form>
            <p className="mt-3 text-center">
                <a href="#" onClick={handleForgotPassword}>
                    Mot de passe oublié ?
                </a>
            </p>
            <div>
                {error && <p style={{ color: "red" }} className='text-center'>{error}</p>}
            </div>
        </div>
    );
}

export default Login;