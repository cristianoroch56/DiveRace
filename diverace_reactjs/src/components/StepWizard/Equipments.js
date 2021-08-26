import React, { useEffect, useState } from "react";
import {
  updateForm,
} from "../../store/actions/StepWizardAction";
import { useDispatch, useSelector } from "react-redux";
import Select from "react-select";
import makeAnimated from "react-select/animated";
import SizeGuideModal from './SizeGuideModal'

const animatedComponents = makeAnimated();
let uuid = 0;
let equipment_pkey = 0;
const emptyequipent = { id: "", size: undefined, title: "", price: 0 };


const Equipments = ({ pax_state }) => {


  const dispatch = useDispatch();
  //const pax_state = useSelector((state) => state.StepWizardReducer.pax);
  const default_pax_options = useSelector((state) => state.StepWizardReducer.rentals_pax_options)
  const rental_equipment_list = useSelector((state) => state.StepWizardReducer.rental_equipment_list);


  const [equipments, setEquipments] = useState([]);
  const [personOption, setPersonOption] = useState([]);
  const [paxEquipments, setPaxEquipments] = useState([])
  const [PaxOptions, setPaxOptions] = useState([]);
  const [persons, setPersons] = useState([]);
  const [defaultPerson, setDefaultPerson] = useState([...default_pax_options]);
  const [openSizeGuide, setOpenSizeGuide] = useState(false);
  const [sizeguidedata, setSizeguidedata] = useState([]);


  /* useEffect(() => {
    setPaxEquipments([...pax_state])
  }, []) */

 /*  useEffect(() => {
    let updated_pax = [...paxEquipments];
    dispatch(updateForm('pax', updated_pax));
  }, [paxEquipments]) */


  useEffect(() => {

    setPaxEquipments([...pax_state])

    dispatch(updateForm('new_equipments_price', 0));
    var rentals_price = 0;
    for (let index = 0; index < pax_state.length; index++) {
      if (pax_state[index].equipments.length > 0) {
        var equipments_array = pax_state[index].equipments;
        var total_price = equipments_array.reduce((prev, next) => prev + Number(next.price), 0);
        var rentals_price = rentals_price + total_price;
      }
    }
    dispatch(updateForm('new_equipments_price', Number(rentals_price)));

  }, [pax_state])

  /* equipments */
  useEffect(() => {
    /* Person options start*/
    const paxOptions = pax_state.map((p, i) => ({
      value: `Person${i + 1}`,
      label: `Person${i + 1}`,
      type: p.type,
      is_used: p.is_used,
      id: i,
    }));
    setPaxOptions([...paxOptions]);
    /* Person options end*/

    const rentals_options = rental_equipment_list.map((r) => ({
      ...r,
      value: r.id,
      label: `S$${r.price} - ${r.title}`,
    }));
    setEquipments([...rentals_options]);

    /* let updated_pax = pax_state.map((p_obj,i) => ({...p_obj, ID: i+1}))
    console.log('updated_pax',updated_pax);
    dispatch(updateForm('pax', [...updated_pax])); */

  }, [rental_equipment_list]);

  
  //console.log('pax_state eq', pax_state);
  const toggleSizeGuide = (data) => {
    setSizeguidedata([...data]);
    setOpenSizeGuide(true);
  }

  const customSelectStyles = {
    // For the select it self, not the options of the select
    control: (styles, { isDisabled }) => {
      return {
        ...styles,
        backgroundColor: isDisabled && "#white",
        borderColor: isDisabled ? "#d9d9d9" : "#d9d9d9",
      };
    },
    option: (styles, { }) => {
      return {
        ...styles,
        fontSize: 15,
        textAlign: "left"
      };
    },
    menuList: (styles, { }) => {
      return {
        ...styles,
        "::-webkit-scrollbar": { width: "2 !important" },
        scrollbarWidth: "none",
        maxHeight: 200,
        overflowY: "auto",
        overflowX: "hidden"
      };
    },
  };

  //--------------------------------------------------------My partails functions---------------------------------//


  const handleAddPaxEquipments = (options) => {

    if (options != null) {
      setDefaultPerson(options);
      dispatch(updateForm('rentals_pax_options', [...options]))

      let new_option = options[options.length - 1].label;
      let person_id = options[options.length - 1].id;

      const optionsValue = options.map(({ value }) => value);

      //check if add

      if (!paxEquipments.some((p) => p.person_id == person_id)) {

        /* set equipments & sizes */

        setPersons([...persons, new_option]);
        let myperson = [...paxEquipments];
        // new logic
        if (paxEquipments[person_id].equipments.length < equipments.length) {

          myperson[person_id].person_id = person_id;
          myperson[person_id].is_used = true;
          myperson[person_id].equipments = [
            ...myperson[person_id].equipments,
            { id: "", size: undefined, title: '', price: 0, default_rental: undefined, default_size: undefined, uuid: ++uuid },
          ];
          setPaxEquipments(myperson);
          /* here */
          dispatch(updateForm('pax', myperson));
        }
      }

      //check if remove
      let difference = persons.filter((x) => !optionsValue.includes(x));
      if (difference.length > 0) {
        setPersons([...optionsValue]);

        /* if person unselect then remove their equipments */
        let person_array = [...paxEquipments];

        const updated_person_array = person_array.map((x, i) => (`Person${i + 1}` === difference[0] ? { ...x, person_id: undefined, is_used: false, equipments: [] } : x));
        setPaxEquipments(updated_person_array);
        /* here */
        dispatch(updateForm('pax', updated_person_array));
      }

    } else {
      /* if user unselect all persons */
      let person_array = [...paxEquipments];
      const updated_person_array = person_array.map((p) => ({ ...p, is_used: false, person_id: undefined, equipments: [] }));
      setPaxEquipments([...updated_person_array]);
      /* here */
      dispatch(updateForm('pax', updated_person_array));
      setDefaultPerson([]);
      dispatch(updateForm('rentals_pax_options', []))
    }
  };

  const changeEquipments = (pax_obj, rental_obj) => {

    var person_id = pax_obj.person_id;
    var equipment_id = pax_obj.equipment;
    var selectedequipmentid = rental_obj.value;

    let myperson = [...paxEquipments];

    const foundequipment = equipments.find((eq) => {
      return eq.id == selectedequipmentid;
    });

    var foundequipmentonobject = myperson[person_id].equipments[equipment_id];
    myperson[person_id].equipments[equipment_id] = foundequipment
      ? {
        id: foundequipment.id,
        rental_equipment_size: foundequipment.rental_equipment_size,
        size: undefined,
        title: foundequipment.title,
        price: foundequipment.price,
        uuid: foundequipmentonobject.uuid,
        default_rental: rental_obj,
        default_size: foundequipmentonobject.default_size
      }
      : emptyequipent;

    setPaxEquipments(myperson);
    /* here */
    dispatch(updateForm('pax', myperson))
  };

  const addEquipments = (index) => {

    let equipments_array = paxEquipments[index].equipments;
    let updated_equipments_array = [...equipments_array, { id: '', title: '', size: undefined, price: 0, default_rental: undefined, default_size: undefined, uuid: ++uuid }];
    const updated_pax_equipments_array = paxEquipments.map((x, i) => (i === index ? { ...x, equipments: updated_equipments_array } : x));
    setPaxEquipments([...updated_pax_equipments_array]);
    /* here */
    dispatch(updateForm('pax', updated_pax_equipments_array))
  }

  const removeEquipments = (index, e) => {
    e.preventDefault();

    let equipment_key = e.currentTarget.getAttribute("data-index");
    let equipments_array = paxEquipments[index].equipments;

    var updated_equipments_array = equipments_array.filter((e, i) => e.uuid != Number(equipment_key));
    const updated_pax_equipments_array = paxEquipments.map((x, i) => (i === index ? { ...x, equipments: updated_equipments_array } : x));
    setPaxEquipments([...updated_pax_equipments_array]);
    /* here */
    dispatch(updateForm('pax', updated_pax_equipments_array))
  }

  const handleSizeSelect = (pax_obj, size_obj) => {
    var person_id = pax_obj.person_id;
    var equipment_id = pax_obj.equipment;
    var size = size_obj.value;

    let myperson = [...paxEquipments];

    myperson[person_id].equipments[equipment_id].size = size;
    myperson[person_id].equipments[equipment_id].default_size = size_obj;
    setPaxEquipments(myperson);
    /* here */
    dispatch(updateForm('pax', myperson))
  };

  return (
    <React.Fragment>
      <section className="p-3 col-sm-12 col-md-10">
        <div className="row date-area border-rd date-wrapper-section equipment-wrapper">
          <div className="cabin-pax-data custom-cabin-pax-data">
            <span className="custom-pax-dropdown col-sm-10 col-9 pr-2">
              <Select
                placeholder="Choose Persons"
                options={PaxOptions}
                isMulti={true}
                onChange={handleAddPaxEquipments}
                styles={customSelectStyles}
                components={animatedComponents}
                defaultValue={defaultPerson}
              ></Select>
            </span>
            {paxEquipments.length > 0 &&
              paxEquipments.filter(opt => opt.person_id !== undefined).map((p_obj, i) => {
                return (
                  <React.Fragment key={p_obj.person_id}>
                    <div className="people-count">
                      <p className="num">
                        <strong>Person{p_obj.person_id + 1}</strong>
                      </p>
                    </div>
                    {p_obj.equipments.length > 0 &&
                      p_obj.equipments.map((e, k) => {
                        return (
                          <div
                            className="row no-gutters mb-3"
                            data-equipment-index={k}
                            key={e.uuid}
                          >
                            <span className="custom-pax-dropdown col-md-7 col-12 pr-1">
                              <Select
                                placeholder="Choose Equipment"
                                autoFocus={true}
                                options={equipments.filter((maineqipment) => {
                                  return !p_obj.equipments?.find(
                                    (myvalue) => myvalue.id == maineqipment.id
                                  );
                                })}
                                isMulti={false}
                                onChange={(e) =>
                                  changeEquipments(
                                    {
                                      person_id: p_obj.person_id,
                                      equipment: k,
                                    },
                                    e
                                  )
                                }
                                styles={customSelectStyles}
                                defaultValue={e.default_rental}
                              ></Select>
                            </span>
                            {/* size */}

                            {e.rental_equipment_size?.length > 0 && (
                              <>
                                <span className="custom-pax-dropdown col-md-3 col-9 pr-1">
                                  <Select
                                    placeholder="Size"
                                    options={e.rental_equipment_size?.map(
                                      (opt, optinbd) => {
                                        return { value: opt, label: opt };
                                      }
                                    )}
                                    isMulti={false}
                                    onChange={(e) =>
                                      handleSizeSelect(
                                        {
                                          person_id: p_obj.person_id,
                                          equipment: k,
                                        },
                                        e
                                      )
                                    }
                                    styles={customSelectStyles}
                                    defaultValue={e.default_size}
                                  ></Select>
                                </span>
                                <span
                                  style={{
                                    fontSize: "12px",

                                    fontWeight: "600",
                                    cursor: "pointer"
                                  }}

                                  className="size-guide"
                                  onClick={() => toggleSizeGuide(e.rental_equipment_size)}
                                >
                                  Size
                                    <br />
                                    Guide
                                  </span>
                              </>
                            )}

                            {/* size */}

                            <span className="custom-add-remove-btn col-md-2 col-3">
                              <span
                                href="#"
                                className={`add_plus_btn ${k !== 0 ? "d-none" : ""
                                  }`}
                                onClick={(equipments.filter((maineqipment) => {
                                  return !p_obj.equipments?.find(
                                    (myvalue) => myvalue.id == maineqipment.id
                                  );
                                }).length === 1 || p_obj.equipments?.filter(
                                  (myvalue) => myvalue.id == ''
                                ).length !== 0) ? undefined : (e) => addEquipments(p_obj.person_id)}
                              >
                                <img
                                  src="./assets/add-icon.svg"
                                  alt="Add Icon"
                                  className="add-icon pr-10"
                                />
                              </span>

                              <span
                                href="#"
                                className={`remove_close_btn ${k == 0 ? "d-none" : ""
                                  }`}
                                data-index={e.uuid}
                                onClick={(e) => removeEquipments(p_obj.person_id, e)}
                              >
                                <img
                                  src="./assets/crose-icon.svg"
                                  alt="Remove Icon"
                                  className="remove-icon pr-10"
                                />
                              </span>
                            </span>
                          </div>
                        );
                      })}
                  </React.Fragment>
                );
              })}
          </div>
        </div>
        {openSizeGuide && (
          <SizeGuideModal isOpen={openSizeGuide} sizelist={sizeguidedata} closePopup={() => setOpenSizeGuide(false)}></SizeGuideModal>
        )}
      </section>
    </React.Fragment>
  );
};

export default Equipments;
