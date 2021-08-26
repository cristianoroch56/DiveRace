import React, { useEffect, useState } from "react";
import {
  updateForm,
} from "../../store/actions/StepWizardAction";
import { useDispatch, useSelector } from "react-redux";
import SizeGuideModal from '../StepWizard/SizeGuideModal'
import SizeOptions from '../StepWizard/AddOns/SizeOptions'

const stateType = "pax";
const Equipments = ({ pax_state }) => {


  const dispatch = useDispatch();
  const rental_equipment_list = useSelector((state) => state.StepWizardReducer.rental_equipment_list);

  const [isDisabled, setIsDisabled] = useState(false);
  const [openSizeGuide, setOpenSizeGuide] = useState(false);
  const [sizeSelectModalOpen, setSizeSelectModalOpen] = useState(false);
  const [sizeOptions, setSizeOptions] = useState([]);
  const [sizeProps, setSizeProps] = useState(undefined);

  
  const checkIsDisabled = () => {
    const local_pax = [...pax_state];
    setIsDisabled(false);
    const findUsedCourseLength = local_pax.filter(p => p.equipment_used == true).length;
    if (findUsedCourseLength === local_pax.length) {
      setIsDisabled(true);
    }
  }

  const toggleSizeSelectModal = (sizes) => {
    setSizeOptions(sizes)
    setSizeSelectModalOpen(true);
  }

  const selectEquipmens = (e, index) => {

    let rentalId = e.currentTarget.getAttribute("data-rental");
 
  }

  const EquipmensUI = ({ paxIndex, equiment }) => {
    return rental_equipment_list.map((rental_obj, i) =>
      <React.Fragment key={i}>
        <div className="col-12 col-sm-6 col-md-6 custom-single_cabin-wrapper single-cabin-wrapper custom-d-flex"
          data-rental={rental_obj.id}
          onClick={(e) => selectEquipmens(e, paxIndex)}
        >
          <div className={`card ${equiment.find((equ) => equ.id === rental_obj.id) ? "cls-border" : ""}`}>
            <div className="main-card-body">
              <div className="card-body">
                <h5 className="card-title">{rental_obj.title}</h5>
                <span className="summary-text-info common-price-text">S$ {rental_obj.price} </span>
              </div>
            </div>
            {/* <span className="info_btn">S$ {rental_obj.price}</span> */}
          </div>


        </div>
      </React.Fragment>
    )
  }


  return (
    <React.Fragment>
      <section className="p-3 col-sm-12 col-md-10">
        <div className="row date-area border-rd date-wrapper-section equipment-wrapper">
          {pax_state.length > 0 ? (
            pax_state.map((pax, index) =>
              <div className="p-2 row rm-padding-mobile" key={index} id={`pax_${index}`}>


                <div className="col-md-5 itinerary-wrapper single-pax">
                  <div className="cls bg-none people-count">
                    <p className="num ">Person{index + 1} </p>
                  </div>

                </div>
                <span className="pull-right" onClick={() => setOpenSizeGuide(true)} style={{ marginRight: 50, color: '#3396d8', cursor: 'pointer', fontWeight: 600, position: 'absolute', right: 0 }}>Size Guide</span>

                <div className="cabin-main-wrapper custom-cabin-wrapper">
                  <div className="row no-gutters row-cabindetails-wrapper">
                    <div className="col-md-12">
                      <div className="row">
                        <div className="card-deck">
                          <EquipmensUI paxIndex={index} equiment={pax.equipments}></EquipmensUI>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
                {openSizeGuide && (
                  <SizeGuideModal isOpen={openSizeGuide} closePopup={() => setOpenSizeGuide(false)}></SizeGuideModal>
                )}
                {
                  sizeSelectModalOpen && (<SizeOptions isOpen={sizeSelectModalOpen} propsData={sizeProps} options={sizeOptions} closePopup={() => setSizeSelectModalOpen(false)}></SizeOptions>)
                }


              </div>

            )
          ) : 'Loading...'}

        </div>
      </section>
    </React.Fragment>
  );
};

export default Equipments;
