<template>
  <section class="box">
    <v-container>
      <v-sheet
          tile
          height="54"
          class="d-flex"
      >
        <v-btn
            icon
            class="ma-2"
            @click="$refs.calendar.prev()"
        >
          <v-icon>mdi-chevron-left</v-icon>
        </v-btn>
        <v-select
            style="color: black"
            v-model="type"
            :items="types"
            dense
            outlined
            hide-details
            class="ma-2"
            label="Type"
        ></v-select>
        <v-select
            v-model="mode"
            :items="modes"
            dense
            outlined
            hide-details
            label="Mode"
            class="ma-2"
        ></v-select>
        <v-select
            v-model="weekday"
            :items="weekdays"
            dense
            outlined
            hide-details
            label="Jours"
            class="ma-2"
        ></v-select>
        <v-spacer></v-spacer>
        <v-btn
            icon
            class="ma-2"
            @click="$refs.calendar.next()"
        >
          <v-icon>mdi-chevron-right</v-icon>
        </v-btn>
      </v-sheet>
      <v-sheet height="600">
        <v-toolbar-title v-if="$refs.calendar" align="center">
          {{ $refs.calendar.title }}
        </v-toolbar-title>
        <v-calendar
            ref="calendar"
            locale="fr"
            v-model="value"
            :weekdays="weekday"
            :type="type"
            :events="events"
            :event-overlap-mode="mode"
            :event-overlap-threshold="30"
            :event-color="getEventColor"
            @change="getEvents"
        ></v-calendar>
      </v-sheet>
    </v-container>
  </section>
</template>

<script>
import Axios from '@/api/axios';
Axios()

export default {
  name: "Promotions",
  data: () => ({
    type: 'month',
    types: [
      { text: 'mois', value: 'month'},
      { text: 'semaine', value: 'week'},
      { text: 'jour', value: 'day'},
      { text: '4 jours', value: '4day'}
    ],
    mode: 'stack',
    modes: [
      { text: 'compacté', value: 'stack' },
      { text: 'colonnes', value: 'column' }
    ],
    weekday: [1, 2, 3, 4, 5, 6, 0],
    weekdays: [
      { text: 'Dim - Sam', value: [0, 1, 2, 3, 4, 5, 6] },
      { text: 'Lun - Dim', value: [1, 2, 3, 4, 5, 6, 0] },
      { text: 'Lun - Ven', value: [1, 2, 3, 4, 5] },
      { text: 'Lun, Mer, Ven', value: [1, 3, 5] },
    ],
    value: '',
    events: [],
    colors: ['blue', 'indigo', 'deep-purple', 'cyan', 'green', 'orange', 'grey darken-1']
  }),
  methods: {
    getEvents ({ start, end }) {
      const events = []

      Axios().get('promotions')
          .then(response => {
            let allEvents = response.data["hydra:member"]
            const min = new Date(`${start.date}T08:00:00`)
            const max = new Date(`${end.date}T18:59:59`)
            allEvents.forEach(event => {
              const first = new Date(event.dateDebut)
              const second = new Date(event.dateFin)
              first.setHours(8,0,0,0)
              second.setHours(9,0,0,0)
              if (min <= first && second <= max) {
                events.push({
                  name: event.nom,
                  start: first,
                  end: second,
                  color: this.colors[this.rnd(0, this.colors.length - 1)],
                  timed: false
                })
              }
            })
          })
          .catch(e => {
            console.log(e)
          })

      this.events = events
    },
    getEventColor (event) {
      return event.color
    },
    rnd (a, b) {
      return Math.floor((b - a + 1) * Math.random()) + a
    },
  },
}
</script>

<style scoped>

</style>