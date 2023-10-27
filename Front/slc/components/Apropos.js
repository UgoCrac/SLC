import React from 'react';
import 'bootstrap/dist/css/bootstrap.css';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import styles from '../styles/Apropos.module.css'

function Apropos() {
    return (
        <div>
            <div className="d-flex justify-content-center">
                <a href="">
                    <img src="img/arrow.png" alt="" style={{ height: 45 }} className='mt-5' />
                </a>
            </div>
            <h4 className={`${styles.montserrat} w-75 m-auto text-center m-1 p-1 mt-5 pt-5`}>Lorem ipsum dolor sit amet consectetur adipisicing elit. Numquam, debitis exercitationem deserunt itaque natus quam magni possimus maiores, aliquid accusantium, delectus accusamus quibusdam soluta quidem enim porro voluptate! Non commodi ex, mollitia voluptas ipsam tenetur libero. Nesciunt quae dolor voluptas exercitationem illo perferendis nihil, ea, eaque quam ex amet tempora possimus pariatur consectetur et facilis ducimus beatae. Distinctio repellendus expedita asperiores. Beatae, nisi minima laborum sit tempore sunt error maxime, odit maiores adipisci laboriosam sequi! Nisi, vel? Fugit, commodi ullam porro sunt facilis enim adipisci blanditiis quia laboriosam ex asperiores fugiat dicta animi impedit repellat. Autem impedit eius esse minima.</h4>
            <div id={`${styles.hexagons}`} >
                <ul id={`${styles.categories}`} className={`${styles.clr}`}>
                    <li>
                        <div className={`${styles.flipContainer}`}>
                            <div className={`${styles.flipper}`}>
                                <div className={`${styles.front}`} style={{ backgroundColor: '#5c4730' }}>
                                    <div className={`${styles.flipContent} ${styles.titleXs}`}>
                                        <p className={`${styles.montserrat}`}>Plâtrerie</p>
                                    </div>
                                </div>
                                <div className={`${styles.back}`}>
                                    <div className={`${styles.flipContent}`}>
                                        <p className={`${styles.montserrat}`} style={{ color: '#5c4730' }}>
                                            "Lorem Ipsum est <br />
                                            simplemet du faux texte <br />
                                            employé dans la composition <br />
                                            et la mise en page <br />
                                            avant impression."
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div className={`${styles.flipContainer}`} >
                            <div className={`${styles.flipper}`}>
                                <div className={`${styles.front}`} style={{ backgroundColor: '#8c6745' }}>
                                    <div className={`${styles.flipContent} ${styles.titleXs}`}>
                                        <p className={`${styles.montserrat}`}>Calicot</p>
                                    </div>
                                </div>
                                <div className={`${styles.back}`}>
                                    <div className={`${styles.flipContent}`}>
                                        <p className={`${styles.montserrat}`} style={{ color: '#8c6745' }}>
                                            "Lorem Ipsum est <br />
                                            simplemet du faux texte <br />
                                            employé dans la composition <br />
                                            et la mise en page <br />
                                            avant impression."
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div className={`${styles.flipContainer}`} >
                            <div className={`${styles.flipper}`}>
                                <div className={`${styles.front}`} style={{ backgroundColor: '#4d331f' }}>
                                    <div className={`${styles.flipContent} ${styles.titleXs}`}>
                                        <p className={`${styles.montserrat}`}>Enduit</p>
                                    </div>
                                </div>
                                <div className={`${styles.back}`}>
                                    <div className={`${styles.flipContent}`}>
                                        <p className={`${styles.montserrat}`} style={{ color: '#4d331f' }}>
                                            "Lorem Ipsum est<br />
                                            simplemet du faux texte <br />
                                            employé dans la composition <br />
                                            et la mise en page <br />
                                            avant impression."
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div className={`${styles.flipContainer}`} >
                            <div className={`${styles.flipper}`}>
                                <div className={`${styles.front}`} style={{ backgroundColor: '#b59c6e' }}>
                                    <div className={`${styles.flipContent} ${styles.titleXs}`}>
                                        <p className={`${styles.montserrat}`}>Menuiserie intérieure</p>
                                    </div>
                                </div>
                                <div className={`${styles.back}`}>
                                    <div className={`${styles.flipContent}`}>
                                        <p className={`${styles.montserrat}`} style={{ color: '#b59c6e' }}>
                                            "Lorem Ipsum est<br />
                                            simplement du faux texte <br />
                                            employé dans la composition <br />
                                            et la mise en page <br />
                                            avant impression."
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div className={`${styles.flipContainer}`} >
                            <div className={`${styles.flipper}`}>
                                <div className={`${styles.front}`} style={{ backgroundColor: '#7f5832' }}>
                                    <div className={`${styles.flipContent} ${styles.titleXs}`}>
                                        <p className={`${styles.montserrat}`}>Menuiserie extérieure</p>
                                    </div>
                                </div>
                                <div className={`${styles.back}`}>
                                    <div className={`${styles.flipContent}`}>
                                        <p className={`${styles.montserrat}`} style={{ color: '#7f5832' }}>
                                            "Lorem Ipsum est<br />
                                            simplement du faux texte <br />
                                            employé dans la composition <br />
                                            et la mise en page <br />
                                            avant impression."
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    );
}

export default Apropos;
