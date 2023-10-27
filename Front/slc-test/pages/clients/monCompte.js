import { useState } from "react";


export default function () {

    const [name, setName] = useState();
    const [prenom, setPrenom] = useState();
    const [email, setEmail] = useState();




    fetch('https://127.0.0.1:8000/api/user/')

}