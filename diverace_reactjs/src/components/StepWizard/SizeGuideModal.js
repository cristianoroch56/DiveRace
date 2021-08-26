import React, { useEffect, useState } from "react";
import ReactModal from "react-modal";



const SizeGuideModal = (props) => {



    const customStyles = {
        content: {
            top: "50%",
            left: "50%",
            right: "auto",
            bottom: "auto",
            transform: "translate(-50%, -50%)",
            width: "100%",
            maxWidth: "800px",
            borderRadius: '10px',
            borderWidth: '0px',
        },
    };

    return (
        <ReactModal
            isOpen={props.isOpen}
            style={customStyles}
            contentLabel="Sizeguide Modal"
            ariaHideApp={false}
            onRequestClose={props.closePopup}
        >
            <button onClick={props.closePopup} className="custom-popup-close">X</button>
            <h4 className="text-center mt-5">Size Guide</h4>
            <div className="row">
                <div className="col-md-12">
                    <div className="size-chart_wrapper">
                        <h2>BCD Size Chart</h2>
                        <div className="table-responsive">
                            <table className="table">
                                <thead className="thead-dark">
                                    <tr>
                                        <th scope="col">Size</th>
                                        <th scope="col"></th>
                                        <th scope="col">XS</th>
                                        <th scope="col">S</th>
                                        <th scope="col">M</th>
                                        <th scope="col">L</th>
                                        <th scope="col">XL</th>
                                        <th scope="col">XXL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">Height</th>
                                        <td>cm</td>
                                        <td>152-165</td>
                                        <td>157-170</td>
                                        <td>170-180</td>
                                        <td>178-185</td>
                                        <td>185-190</td>
                                        <td>188-195</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Weight</th>
                                        <td>kg</td>
                                        <td>45-46</td>
                                        <td>54-70</td>
                                        <td>68-79</td>
                                        <td>77-95</td>
                                        <td>88-108</td>
                                        <td>104-122</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <h2>Wetsuit Size Chart</h2>
                        <div className="table-responsive">
                            <table className="table">

                                <thead className="thead-dark">
                                    <tr>
                                        <th scope="col">Size</th>
                                        <th scope="col"></th>
                                        <th scope="col">XS</th>
                                        <th scope="col">S</th>
                                        <th scope="col">M</th>
                                        <th scope="col">L</th>
                                        <th scope="col">XL</th>
                                        <th scope="col">XXL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">Height</th>
                                        <td>cm</td>
                                        <td>156-166</td>
                                        <td>163-171</td>
                                        <td>163-171</td>
                                        <td>170-178</td>
                                        <td>172-181</td>
                                        <td>172-181</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Upper Chest</th>
                                        <td>cm</td>
                                        <td>83</td>
                                        <td>89</td>
                                        <td>99</td>
                                        <td>100</td>
                                        <td>105</td>
                                        <td>110</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Waist</th>
                                        <td>cm</td>
                                        <td>65</td>
                                        <td>70</td>
                                        <td>80</td>
                                        <td>80</td>
                                        <td>85</td>
                                        <td>90</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Hip</th>
                                        <td>cm</td>
                                        <td>80</td>
                                        <td>85</td>
                                        <td>95</td>
                                        <td>96</td>
                                        <td>101</td>
                                        <td>106</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </ReactModal >
    );
};

export default SizeGuideModal;
