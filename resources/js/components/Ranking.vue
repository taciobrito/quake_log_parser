<template>
	<div class="container">
		<div class="ranking">
			<header>
				<h2>{{ titulo }}</h2>
			</header>
			
			<section>
				<div class="search">
					<form @submit.prevent="pesquisa">
						<div class="input-group mb-3">
						  <input type="text" class="form-control" v-model="buscar" placeholder="Buscar por nome">
						  <div class="input-group-append">
						    <button type="submit" class="btn btn-secondary">Buscar</button>
						  </div>
						</div>
					</form>
				</div>

				<div class="list">
					<table class="table">
						<thead>
							<tr>
								<th>Name</th>
								<th class="text-right">
									<img src="/svg/poison.svg" class="poison">
									Kills
								</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(kill, idx) in listKills" :key="idx">
								<td>{{ kill.name }}</td>
								<td class="text-center" :style="{ color: getColorValue(kill.kills) }">{{ kill.kills }}</td>
							</tr>
						</tbody>
					</table>
				</div>
			</section>
		</div>

		<div class="loading" v-if="loading">
      <img src="/svg/loading.svg" alt="">
    </div>
	</div>
</template>

<script>
	export default {
		name: 'ranking',
		created() {
			this.getKills();
		},
		data() {
			return {
				titulo: 'Ranking',
				kills: [],
				buscar: '',
				filtrarPesquisa: false,
				loading: false,
			}
		},
		methods: {
			getKills() {
				this.loading = true;
				axios.get('kills')
          .then(response => this.kills = response.data)
          .catch(error => alert('Houve um erro ao carregar a lista de Kills!'))
          .finally(() => this.loading = false);
			},
			pesquisa() {
				if (this.buscar != '') {
					this.filtrarPesquisa = true;
				} else {
					this.filtrarPesquisa = false;
				}
			},
			getColorValue(value) {
				return value > 0 ? '#fff' : '#f7a600';
			},
		},
		computed: {
			listKills() {
				function compare(a, b) {
		      if (a.kills < b.kills)
		        return 1;
		      if (a.kills > b.kills)
		        return -1;
		      return 0;
		    }

		    if (this.filtrarPesquisa) {
					return this.kills.filter(kill => kill.name.indexOf(this.buscar) > -1)
						.sort(compare);
					this.filtrarPesquisa = false;
		    } else {
					return this.kills.sort(compare);
		    }
			}
		}
	}
</script>

<style lang="sass">
	body
		font-family: 'Roboto', sans-serif
		background: #dadada

	.ranking
		margin: 30px auto 0 auto
		width: 400px
		h2
			text-transform: uppercase
			font-weight: 700

	.search
		input
			background: #dadada
			border-color: #fff
		input:focus
			background: #f3f2f2
		button
			border-radius: 0
			color: #f7a600
			width: 150px

	.list 
		table
			thead
				background: #f7a600
				padding: 0 !important
				tr
					th
						text-transform: uppercase
						font-size: 22px
						font-weight: 700
			tbody
				color: #fff
				tr:nth-child(even)
					background: #646363
				tr:nth-child(odd)
					background: #7c7b7b
				tr:hover
					background: rgba(124,123,123,.4)

	.poison
		width: 27px

	.loading
		background-color: rgba(255,255,255,.5)
		position: fixed
		display: flex
		align-items: center
		justify-content: center
		z-index: 9999
		width: 100%
		height: 100vh
		top: 0
</style>